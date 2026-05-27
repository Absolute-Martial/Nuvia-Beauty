<?php

namespace App\Domains\Storage\Controllers;

use App\Domains\Storage\Models\MediaAsset;
use App\Domains\Storage\Services\MediaAssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Marvel\Database\Models\User;

class StorageController extends Controller
{
    public function __construct(
        protected MediaAssetService $mediaAssetService,
    ) {
    }

    public function uploadSlots(Request $request): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $validated = $request->validate([
            'purpose' => ['required', Rule::in(array_keys(MediaAssetService::PURPOSE_DISK_MAP))],
            'asset_type' => ['required', 'string', 'max:100'],
            'owner_type' => ['required', Rule::in(['user', 'profile', 'shop'])],
            'owner_id' => ['required', 'integer', 'min:1'],
            'profile_id' => ['nullable', 'integer', 'min:1'],
            'shop_id' => ['nullable', 'integer', 'min:1'],
            'session_id' => ['nullable', 'string', 'max:100'],
            'file_name' => ['required', 'string', 'max:255'],
            'content_type' => [
                'required',
                'string',
                Rule::in([
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'image/heic',
                    'image/heif',
                    'application/pdf',
                ]),
            ],
            'size_bytes' => ['required', 'integer', 'min:1', 'max:10485760'],
            'checksum_sha256' => ['nullable', 'string', 'size:64'],
        ]);

        $slot = $this->mediaAssetService->createUploadSlot($validated, $actor);

        return response()->json([
            'data' => $slot->toArray(),
        ], 201);
    }

    public function confirm(Request $request, int $mediaId): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $mediaAsset = MediaAsset::findOrFail($mediaId);
        Gate::forUser($actor)->authorize('confirm', $mediaAsset);

        return response()->json([
            'data' => $this->mediaAssetService->confirmUpload($mediaAsset),
        ]);
    }

    public function downloadUrl(Request $request, int $mediaId): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $mediaAsset = MediaAsset::findOrFail($mediaId);
        Gate::forUser($actor)->authorize('download', $mediaAsset);

        return response()->json([
            'data' => $this->mediaAssetService->createDownloadUrl($mediaAsset),
        ]);
    }

    public function destroy(Request $request, int $mediaId): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $mediaAsset = MediaAsset::findOrFail($mediaId);
        Gate::forUser($actor)->authorize('delete', $mediaAsset);

        return response()->json([
            'data' => $this->mediaAssetService->discard($mediaAsset),
        ]);
    }
}
