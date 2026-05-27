<?php

namespace App\Domains\Beauty\Controllers;

use App\Domains\Beauty\Models\BeautyProductMapping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Marvel\Database\Models\Product;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\User;
use Marvel\Enums\Permission;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BeautyProductMappingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BeautyProductMapping::query()->with(['product.shop', 'product.tags']);

        if ($request->filled('product_id')) {
            $query->where('product_id', (int) $request->input('product_id'));
        }

        if ($request->filled('shop_id')) {
            $shopId = (int) $request->input('shop_id');
            $query->whereHas('product', fn ($builder) => $builder->where('shop_id', $shopId));
        }

        return response()->json([
            'data' => $query->paginate((int) $request->input('limit', 15)),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $validated = $this->validatePayload($request, null);
        $product = Product::findOrFail((int) $validated['product_id']);
        $this->authorizeProduct($actor, $product);

        $mapping = BeautyProductMapping::updateOrCreate(
            ['product_id' => $product->id],
            $this->mappingAttributes($validated)
        );

        return response()->json([
            'data' => $mapping->load(['product.shop', 'product.tags']),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();
        $mapping = BeautyProductMapping::findOrFail($id);
        $product = Product::findOrFail((int) $mapping->product_id);
        $this->authorizeProduct($actor, $product);

        $validated = $this->validatePayload($request, $mapping);
        $mapping->fill($this->mappingAttributes($validated))->save();

        return response()->json([
            'data' => $mapping->fresh()->load(['product.shop', 'product.tags']),
        ]);
    }

    protected function validatePayload(Request $request, ?BeautyProductMapping $mapping): array
    {
        return $request->validate([
            'product_id' => [
                Rule::requiredIf(!$mapping),
                'integer',
                'exists:products,id',
            ],
            'concern_tags' => ['nullable', 'array'],
            'concern_tags.*' => ['string', 'max:50'],
            'skin_type_tags' => ['nullable', 'array'],
            'skin_type_tags.*' => ['string', 'max:50'],
            'tone_tags' => ['nullable', 'array'],
            'tone_tags.*' => ['string', 'max:50'],
            'undertone_tags' => ['nullable', 'array'],
            'undertone_tags.*' => ['string', 'max:50'],
            'ingredient_tags' => ['nullable', 'array'],
            'ingredient_tags.*' => ['string', 'max:50'],
            'avoid_tags' => ['nullable', 'array'],
            'avoid_tags.*' => ['string', 'max:50'],
            'explanation_template' => ['nullable', 'string'],
        ]);
    }

    protected function mappingAttributes(array $validated): array
    {
        return [
            'product_id' => $validated['product_id'] ?? null,
            'concern_tags' => $validated['concern_tags'] ?? [],
            'skin_type_tags' => $validated['skin_type_tags'] ?? [],
            'tone_tags' => $validated['tone_tags'] ?? [],
            'undertone_tags' => $validated['undertone_tags'] ?? [],
            'ingredient_tags' => $validated['ingredient_tags'] ?? [],
            'avoid_tags' => $validated['avoid_tags'] ?? [],
            'explanation_template' => $validated['explanation_template'] ?? null,
        ];
    }

    protected function authorizeProduct(User $actor, Product $product): void
    {
        if ($actor->hasPermissionTo(Permission::SUPER_ADMIN)) {
            return;
        }

        $shop = Shop::with('staffs')->find($product->shop_id);

        if (!$shop) {
            throw new AccessDeniedHttpException('Mapped product shop could not be resolved.');
        }

        if ($actor->hasPermissionTo(Permission::STORE_OWNER) && (int) $shop->owner_id === (int) $actor->id) {
            return;
        }

        if ($actor->hasPermissionTo(Permission::STAFF) && $shop->staffs->contains('id', $actor->id)) {
            return;
        }

        throw new AccessDeniedHttpException('You do not have permission to manage beauty mappings for that product.');
    }
}
