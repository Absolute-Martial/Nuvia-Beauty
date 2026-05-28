<?php

namespace App\Domains\Beauty\Controllers;

use App\Domains\Beauty\Models\BeautyProductMapping;
use App\Domains\Beauty\Models\BeautyProductSignal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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

    public function overview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'shop_id' => ['nullable', 'integer', 'exists:shops,id'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $baseQuery = Product::query()
            ->with(['shop:id,name', 'type:id,name'])
            ->when(
                !empty($validated['name']),
                fn (Builder $query) => $query->where('name', 'like', '%' . $validated['name'] . '%')
            )
            ->when(
                !empty($validated['shop_id']),
                fn (Builder $query) => $query->where('shop_id', (int) $validated['shop_id'])
            )
            ->orderByDesc('updated_at');

        $paginator = $baseQuery->paginate((int) ($validated['limit'] ?? 20));
        $productIds = collect($paginator->items())->pluck('id')->map(fn ($id) => (int) $id)->all();

        $mappings = BeautyProductMapping::query()
            ->whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');

        $signals = BeautyProductSignal::query()
            ->whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');

        return response()->json([
            'data' => [
                'summary' => $this->overviewSummary(clone $baseQuery),
                'products' => [
                    'data' => collect($paginator->items())
                        ->map(fn (Product $product) => $this->serializeOverviewProduct(
                            $product,
                            $mappings->get((int) $product->id),
                            $signals->get((int) $product->id),
                        ))
                        ->values()
                        ->all(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ],
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

    protected function overviewSummary(Builder $productQuery): array
    {
        $productIds = $productQuery->pluck('id')->map(fn ($id) => (int) $id);
        $totalProducts = $productIds->count();

        if ($totalProducts === 0) {
            return [
                'total_products' => 0,
                'mapped_products' => 0,
                'unmapped_products' => 0,
                'partial_products' => 0,
                'ready_products' => 0,
            ];
        }

        $mappings = BeautyProductMapping::query()
            ->whereIn('product_id', $productIds->all())
            ->get();

        $mappedProducts = $mappings->count();
        $partialProducts = $this->countByStatus($mappings, BeautyProductMapping::STATUS_PARTIAL);
        $readyProducts = $this->countByStatus($mappings, BeautyProductMapping::STATUS_READY);

        return [
            'total_products' => $totalProducts,
            'mapped_products' => $mappedProducts,
            'unmapped_products' => max($totalProducts - $mappedProducts, 0),
            'partial_products' => $partialProducts,
            'ready_products' => $readyProducts,
        ];
    }

    protected function serializeOverviewProduct(
        Product $product,
        ?BeautyProductMapping $mapping,
        ?BeautyProductSignal $signal,
    ): array {
        return [
            'id' => (int) $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'shop_id' => $product->shop_id ? (int) $product->shop_id : null,
            'shop_name' => $product->shop?->name,
            'type_name' => $product->type?->name,
            'mapping_id' => $mapping?->id,
            'mapping_status' => BeautyProductMapping::statusFor($mapping),
            'mapping_dimension_count' => $mapping?->recommendationDimensionCount() ?? 0,
            'has_avoid_tags' => $mapping ? !empty($mapping->avoid_tags) : false,
            'has_explanation_template' => $mapping ? !empty($mapping->explanation_template) : false,
            'signal' => $signal ? [
                'weighted_score' => $signal->weighted_score,
                'views' => (int) $signal->view_count,
                'add_to_cart' => (int) $signal->add_to_cart_count,
                'purchases' => (int) $signal->purchase_count,
                'last_recomputed_at' => optional($signal->last_recomputed_at)?->toIso8601String(),
            ] : null,
        ];
    }

    protected function countByStatus(Collection $mappings, string $status): int
    {
        return $mappings->filter(
            fn (BeautyProductMapping $mapping) => $mapping->recommendationStatus() === $status
        )->count();
    }
}
