<?php

namespace Marvel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Marvel\Database\Models\Product;
use Marvel\Services\Zyro\ZyroSettingsService;
use Marvel\Services\Zyro\ZyroTryOnStorageService;
use Marvel\Services\Zyro\ZyroYouCamService;
use Throwable;

class ZyroTryOnController extends CoreController
{
    public function storePhoto(
        Request $request,
        ZyroSettingsService $settings,
        ZyroTryOnStorageService $storage
    ) {
        $youcam = $settings->all()['youcam'] ?? [];
        $allowedMimeTypes = implode(',', (array) ($youcam['allowedMimeTypes'] ?? ['image/jpeg', 'image/png']));
        $maxKb = ((int) ($youcam['maxUploadMb'] ?? 10)) * 1024;

        $validated = $request->validate([
            'photo' => ['required', 'file', 'mimetypes:' . $allowedMimeTypes, 'max:' . $maxKb],
        ]);

        $photo = $storage->storeSourcePhoto($validated['photo'], $request->user()?->id);

        return response()->json([
            'photo_id' => $photo['photo_id'],
            'mime_type' => $photo['mime_type'],
            'created_at' => $photo['created_at'],
        ], 201);
    }

    public function createTask(
        Request $request,
        ZyroSettingsService $settings,
        ZyroTryOnStorageService $storage,
        ZyroYouCamService $youCam
    ) {
        $flags = $settings->all()['featureFlags'] ?? [];
        if (!(bool) ($flags['enablePhotoTryOn'] ?? true)) {
            return response()->json(['message' => 'Photo try-on is disabled.'], 403);
        }

        $validated = $request->validate([
            'photo_id' => ['required', 'uuid'],
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);
        $product->loadMissing(['tags', 'variations.attribute']);
        $sourcePhoto = $storage->getSourcePhoto($validated['photo_id']);
        $tryOnMetadata = $this->tryOnMetadata($product);

        if (!$sourcePhoto) {
            return response()->json(['message' => 'Source photo not found.'], 404);
        }

        $taskId = (string) Str::uuid();
        $taskStatus = $youCam->configured() ? 'queued' : 'configuration_required';
        $remoteTaskId = null;
        $remoteResponse = null;

        if ($youCam->configured()) {
            try {
                $vtoType = $this->resolveVtoType($product, $tryOnMetadata);
                $sourceFileId = $youCam->createUploadedSourceFile($sourcePhoto, $vtoType);
                $payload = ['src_file_id' => $sourceFileId];
                $referenceImage = (string) (Arr::get($tryOnMetadata, 'garmentReferenceImage') ?: ($product->image['original'] ?? $product->image ?? ''));

                $payload['ref_file_url'] = $referenceImage;

                if ($vtoType === 'watch') {
                    $payload['watch_wearing_location'] = (float) Arr::get($tryOnMetadata, 'watchWearingLocation', 0.0);
                    $payload['watch_shadow_intensity'] = (float) Arr::get($tryOnMetadata, 'watchShadowIntensity', 0.15);
                    $payload['watch_ambient_light_intensity'] = (float) Arr::get($tryOnMetadata, 'watchAmbientLightIntensity', 1.0);
                    $watchAnchorPoints = Arr::get($tryOnMetadata, 'watchAnchorPoints', []);
                    if (is_array($watchAnchorPoints) && !empty($watchAnchorPoints)) {
                        $payload['watch_anchor_points'] = $watchAnchorPoints;
                    }
                } elseif ($vtoType === 'shoes') {
                    $payload['gender'] = (string) Arr::get($tryOnMetadata, 'shoeGender', Arr::get($tryOnMetadata, 'gender', 'female'));
                    $payload['style'] = (string) Arr::get($tryOnMetadata, 'shoeStyle', Arr::get($tryOnMetadata, 'style', 'random'));
                } elseif ($vtoType === 'bags') {
                    $payload['gender'] = (string) Arr::get($tryOnMetadata, 'bagGender', Arr::get($tryOnMetadata, 'gender', 'female'));
                    $payload['style'] = (string) Arr::get($tryOnMetadata, 'bagStyle', Arr::get($tryOnMetadata, 'style', 'random'));
                } else {
                    $payload['garment_category'] = (string) Arr::get($tryOnMetadata, 'garmentType', 'auto');
                }

                $remoteResponse = $youCam->createTask($vtoType, $payload);
                $remoteTaskId = Arr::get($remoteResponse, 'data.task_id');
                $taskStatus = $remoteTaskId ? 'processing' : 'failed';
            } catch (Throwable $exception) {
                $remoteResponse = ['error' => $exception->getMessage()];
                $taskStatus = 'failed';
            }
        }

        $task = $storage->putTask([
            'task_id' => $taskId,
            'status' => $taskStatus,
            'remote_task_id' => $remoteTaskId,
            'remote_response' => $remoteResponse,
            'photo_id' => $validated['photo_id'],
            'product_id' => $product->id,
            'garment' => [
                'vto_type' => $this->resolveVtoType($product, $tryOnMetadata),
                'type' => Arr::get($tryOnMetadata, 'garmentType'),
                'reference_image' => Arr::get($tryOnMetadata, 'garmentReferenceImage'),
                'body_zone' => Arr::get($tryOnMetadata, 'bodyZone'),
                'style_tags' => Arr::get($tryOnMetadata, 'styleTags', []),
                'watch_wearing_location' => Arr::get($tryOnMetadata, 'watchWearingLocation'),
                'watch_shadow_intensity' => Arr::get($tryOnMetadata, 'watchShadowIntensity'),
                'watch_ambient_light_intensity' => Arr::get($tryOnMetadata, 'watchAmbientLightIntensity'),
                'watch_anchor_points' => Arr::get($tryOnMetadata, 'watchAnchorPoints', []),
                'shoe_gender' => Arr::get($tryOnMetadata, 'shoeGender'),
                'shoe_style' => Arr::get($tryOnMetadata, 'shoeStyle'),
                'bag_gender' => Arr::get($tryOnMetadata, 'bagGender'),
                'bag_style' => Arr::get($tryOnMetadata, 'bagStyle'),
            ],
            'api_family' => $this->resolveVtoType($product, $tryOnMetadata),
            'created_at' => now()->toISOString(),
        ]);

        return response()->json($task, 202);
    }

    public function showTask(
        string $taskId,
        ZyroTryOnStorageService $storage,
        ZyroYouCamService $youCam
    ) {
        $task = $storage->getTask($taskId);

        if (!$task) {
            return response()->json(['message' => 'Try-on task not found.'], 404);
        }

        $terminalStatuses = ['success', 'failed', 'configuration_required'];
        if ($youCam->configured() && !empty($task['remote_task_id']) && !in_array($task['status'], $terminalStatuses, true)) {
            try {
                $vtoType = (string) Arr::get($task, 'garment.vto_type', 'clothes');
                $remoteResponse = $youCam->getTask($vtoType, (string) $task['remote_task_id']);
                $remoteStatus = Arr::get($remoteResponse, 'data.task_status');
                $resultUrl = Arr::get($remoteResponse, 'data.results.url');

                $task['remote_response'] = $remoteResponse;

                if ($remoteStatus === 'success' && $resultUrl) {
                    $task['status'] = 'success';
                    $task['result_path'] = $youCam->storeResultImage($taskId, $resultUrl);
                    $task['result_url'] = $resultUrl;
                } elseif (Arr::get($remoteResponse, 'data.error')) {
                    $task['status'] = 'failed';
                } else {
                    $task['status'] = $remoteStatus ?: $task['status'];
                }

                $task = $storage->putTask($task);
            } catch (Throwable $exception) {
                $task['status'] = 'failed';
                $task['remote_response'] = ['error' => $exception->getMessage()];
                $task = $storage->putTask($task);
            }
        }

        return response()->json($task);
    }

    private function resolveVtoType(Product $product, array $tryOnMetadata): string
    {
        $inferredType = $this->inferVtoTypeFromProduct($product);
        if ($inferredType) {
            return $inferredType;
        }

        $vtoType = strtolower((string) Arr::get($tryOnMetadata, 'vtoType', Arr::get($tryOnMetadata, 'garmentType', 'clothes')));
        if ($vtoType === 'shoe') {
            $vtoType = 'shoes';
        }
        if ($vtoType === 'bag') {
            $vtoType = 'bags';
        }

        return in_array($vtoType, ['clothes', 'watch', 'shoes', 'bags'], true) ? $vtoType : 'clothes';
    }

    private function inferVtoTypeFromProduct(Product $product): ?string
    {
        $text = $this->productSignalText($product);

        if ($text === '') {
            return null;
        }

        $signals = [
            'watch' => [
                'watch',
                'wristwatch',
                'timepiece',
                'bracelet watch',
            ],
            'bags' => [
                'bag',
                'bags',
                'handbag',
                'purse',
                'tote',
                'clutch',
                'backpack',
                'satchel',
                'crossbody',
            ],
            'shoes' => [
                'shoe',
                'shoes',
                'sneaker',
                'sneakers',
                'trainer',
                'boots',
                'boot',
                'loafer',
                'loafers',
                'heel',
                'heels',
                'pump',
                'pumps',
                'slipper',
                'slippers',
                'footwear',
            ],
            'clothes' => [
                'shirt',
                'shirts',
                'top',
                'tops',
                't-shirt',
                'tshirt',
                'dress',
                'jacket',
                'coat',
                'hoodie',
                'pant',
                'pants',
                'trouser',
                'trousers',
                'skirt',
                'blouse',
                'sweater',
                'suit',
                'kurta',
                'saree',
                'jean',
                'jeans',
                'legging',
                'leggings',
            ],
        ];

        foreach (['watch', 'bags', 'shoes', 'clothes'] as $type) {
            foreach ($signals[$type] as $signal) {
                if (Str::contains($text, $signal)) {
                    return $type;
                }
            }
        }

        return null;
    }

    private function productSignalText(Product $product): string
    {
        $tagText = collect($product->tags ?? [])
            ->flatMap(function ($tag) {
                return [
                    $tag->name ?? null,
                    $tag->slug ?? null,
                    $tag->details ?? null,
                ];
            })
            ->filter()
            ->implode(' ');

        $attributeText = collect($product->variations ?? [])
            ->flatMap(function ($attributeValue) {
                $attributeName = optional($attributeValue->attribute)->name;

                return [
                    $attributeName,
                    $attributeValue->value ?? null,
                    $attributeValue->slug ?? null,
                    $attributeValue->meta ?? null,
                ];
            })
            ->filter()
            ->implode(' ');

        return strtolower(trim($tagText . ' ' . $attributeText));
    }

    private function tryOnMetadata(Product $product): array
    {
        $metas = $product->metas;

        if (is_array($metas)) {
            return collect($metas)->mapWithKeys(function ($meta) {
                return [$meta['key'] => $meta['value'] ?? null];
            })->all();
        }

        if ($metas instanceof \Illuminate\Support\Collection) {
            return $metas->mapWithKeys(function ($meta) {
                return [$meta->key => $meta->value ?? null];
            })->all();
        }

        return [];
    }

}
