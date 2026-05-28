<?php

namespace App\Domains\Beauty\Services;

use App\Domains\Beauty\DTO\KaggleCatalogItem;
use App\Domains\Beauty\Models\BeautyProductMapping;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Marvel\Database\Models\Product;
use RuntimeException;
use ZipArchive;

class KaggleBeautyCatalogImportService
{
    public function import(string $sourcePath, int $limit = 40, bool $dryRun = false): array
    {
        if ($limit < 1) {
            throw new RuntimeException('The import limit must be at least 1.');
        }

        $csvPath = $this->resolveCsvPath($sourcePath);
        $items = $this->readCatalogItems($csvPath);
        $products = Product::query()
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($items->isEmpty()) {
            throw new RuntimeException('The Kaggle dataset did not contain any usable rows.');
        }

        if ($products->isEmpty()) {
            return [
                'source_path' => $csvPath,
                'dataset_rows' => 0,
                'products_targeted' => 0,
                'mappings_written' => 0,
                'family_counts' => [],
                'records' => [],
            ];
        }

        $records = [];
        $familyCounts = [];

        foreach ($products as $index => $product) {
            $catalogItem = $this->selectCatalogItem($items, $index);
            $family = $catalogItem->family();
            $familyCounts[$family] = ($familyCounts[$family] ?? 0) + 1;

            $payload = $this->buildMappingPayload($product, $catalogItem, $index, $family);
            if (!$dryRun) {
                BeautyProductMapping::updateOrCreate(
                    ['product_id' => $product->id],
                    $payload,
                );
            }

            $records[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'source_row' => $catalogItem->rowIndex,
                'catalog_family' => $family,
                'source_title' => $catalogItem->title(),
                'source_summary' => $catalogItem->summary(),
                'status' => $dryRun
                    ? BeautyProductMapping::statusFor(BeautyProductMapping::query()->where('product_id', $product->id)->first())
                    : BeautyProductMapping::statusFor(
                        BeautyProductMapping::query()->where('product_id', $product->id)->first()
                    ),
                'action' => $dryRun ? 'previewed' : 'upserted',
            ];
        }

        return [
            'source_path' => $csvPath,
            'dataset_rows' => $items->count(),
            'products_targeted' => $products->count(),
            'mappings_written' => count($records),
            'family_counts' => $familyCounts,
            'records' => $records,
        ];
    }

    public function writeReport(array $report, string $reportPath): void
    {
        $directory = dirname($reportPath);
        File::ensureDirectoryExists($directory);

        File::put(
            $reportPath,
            json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}',
        );
    }

    protected function resolveCsvPath(string $sourcePath): string
    {
        $candidate = $this->normalizePath($sourcePath);

        if (!File::exists($candidate)) {
            throw new RuntimeException("Source file not found: {$sourcePath}");
        }

        if (!Str::endsWith(Str::lower($candidate), '.zip')) {
            return $candidate;
        }

        return $this->extractFirstCsvFromZip($candidate);
    }

    protected function normalizePath(string $sourcePath): string
    {
        $realPath = realpath($sourcePath);

        if ($realPath !== false) {
            return $realPath;
        }

        if (Str::startsWith($sourcePath, DIRECTORY_SEPARATOR)) {
            return $sourcePath;
        }

        return base_path(ltrim($sourcePath, DIRECTORY_SEPARATOR));
    }

    protected function extractFirstCsvFromZip(string $zipPath): string
    {
        if (!class_exists(ZipArchive::class)) {
            throw new RuntimeException('Zip support is not available in this PHP runtime.');
        }

        $zip = new ZipArchive();
        $opened = $zip->open($zipPath);

        if ($opened !== true) {
            throw new RuntimeException("Unable to open ZIP archive: {$zipPath}");
        }

        $targetEntry = null;

        for ($index = 0; $index < $zip->count(); $index++) {
            $stat = $zip->statIndex($index);
            $name = $stat['name'] ?? null;

            if ($name && Str::endsWith(Str::lower($name), '.csv')) {
                $targetEntry = $name;
                break;
            }
        }

        if (!$targetEntry) {
            $zip->close();
            throw new RuntimeException("No CSV file was found inside {$zipPath}.");
        }

        $extractDir = storage_path('app/beauty/kaggle-imports/' . Str::slug(pathinfo($zipPath, PATHINFO_FILENAME)) . '-' . Str::random(8));
        File::ensureDirectoryExists($extractDir);

        if (!$zip->extractTo($extractDir, [$targetEntry])) {
            $zip->close();
            throw new RuntimeException("Unable to extract {$targetEntry} from {$zipPath}.");
        }

        $zip->close();

        return $extractDir . DIRECTORY_SEPARATOR . $targetEntry;
    }

    protected function readCatalogItems(string $csvPath): Collection
    {
        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            throw new RuntimeException("Unable to open CSV file: {$csvPath}");
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return collect();
        }

        $header = array_map(fn ($value) => $this->normalizeHeader((string) $value), $header);

        $items = collect();
        $rowIndex = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $normalized = $this->normalizeRow($header, $row, $rowIndex);
            if ($normalized) {
                $items->push($normalized);
                $rowIndex++;
            }
        }

        fclose($handle);

        return $items;
    }

    protected function normalizeRow(array $header, array $row, int $rowIndex): ?KaggleCatalogItem
    {
        $values = [];

        foreach ($header as $columnIndex => $columnName) {
            $values[$columnName] = $this->cleanValue($row[$columnIndex] ?? null);
        }

        $brand = $this->pickValue($values, ['brand', 'brand_name', 'maker', 'manufacturer']);
        $name = $this->pickValue($values, ['name', 'product_name', 'title', 'product_title', 'item_name', 'product']);
        $category = $this->pickValue($values, ['category', 'sub_category', 'subcategory', 'product_type', 'department', 'group']);
        $description = $this->pickValue($values, ['description', 'details', 'product_description', 'overview', 'summary']);
        $ingredients = $this->pickValue($values, ['ingredients', 'ingredient_list', 'formulation', 'formula']);
        $price = $this->pickValue($values, ['price', 'sale_price', 'mrp', 'amount', 'cost']);
        $imageUrl = $this->pickValue($values, ['image', 'image_url', 'imageurl', 'url', 'img', 'thumbnail']);
        $shade = $this->pickValue($values, ['shade', 'shade_name', 'shade_label', 'color_name']);
        $color = $this->pickValue($values, ['color', 'colour', 'colors', 'colour_name']);

        if (!$brand && !$name && !$category && !$description) {
            return null;
        }

        return new KaggleCatalogItem(
            rowIndex: $rowIndex,
            raw: $values,
            brand: $brand,
            name: $name,
            category: $category,
            description: $description,
            ingredients: $ingredients,
            price: $price,
            imageUrl: $imageUrl,
            shade: $shade,
            color: $color,
        );
    }

    protected function buildMappingPayload(Product $product, KaggleCatalogItem $item, int $index, string $family): array
    {
        [$skinTypes, $concerns, $tones, $undertones, $ingredients, $avoidTags] = $this->buildTagSets($item, $index, $family);

        return [
            'concern_tags' => $concerns,
            'skin_type_tags' => $skinTypes,
            'tone_tags' => $tones,
            'undertone_tags' => $undertones,
            'ingredient_tags' => $ingredients,
            'avoid_tags' => $avoidTags,
            'explanation_template' => $this->buildExplanationTemplate($product, $item, $family, $concerns, $ingredients),
        ];
    }

    protected function buildTagSets(KaggleCatalogItem $item, int $index, string $family): array
    {
        $text = $item->searchableText();
        $toneRotation = ['fair', 'light', 'medium', 'tan', 'deep'];
        $undertoneRotation = ['warm', 'cool', 'neutral', 'olive'];

        $familyBlueprints = [
            'skincare' => [
                'skin' => ['dry', 'normal', 'combination'],
                'concern' => ['dryness', 'dullness', 'texture'],
                'ingredients' => ['hyaluronic_acid', 'niacinamide', 'ceramide'],
                'avoid' => ['fragrance'],
            ],
            'makeup' => [
                'skin' => ['combination', 'normal', 'oily'],
                'concern' => ['uneven_tone', 'texture', 'long_wear'],
                'ingredients' => ['mica', 'squalane', 'vitamin_c'],
                'avoid' => ['fragrance'],
            ],
            'lip' => [
                'skin' => ['normal', 'dry', 'combination'],
                'concern' => ['color_payoff', 'comfort', 'hydration'],
                'ingredients' => ['shea_butter', 'vitamin_e'],
                'avoid' => ['alcohol'],
            ],
            'eye' => [
                'skin' => ['sensitive', 'normal', 'combination'],
                'concern' => ['definition', 'smudge_resistance', 'lift'],
                'ingredients' => ['vitamin_e', 'peptides'],
                'avoid' => ['fragrance'],
            ],
            'haircare' => [
                'skin' => ['dry', 'normal', 'sensitive'],
                'concern' => ['frizz', 'dryness', 'scalp_balance'],
                'ingredients' => ['argan_oil', 'keratin', 'ceramide'],
                'avoid' => ['sulfates'],
            ],
            'bodycare' => [
                'skin' => ['dry', 'normal', 'sensitive'],
                'concern' => ['hydration', 'smoothness', 'comfort'],
                'ingredients' => ['glycerin', 'shea_butter', 'ceramide'],
                'avoid' => ['fragrance'],
            ],
            'fragrance' => [
                'skin' => ['normal', 'combination'],
                'concern' => ['layering', 'longevity', 'signature_scent'],
                'ingredients' => ['alcohol', 'fragrance'],
                'avoid' => ['essential_oil'],
            ],
            'nail' => [
                'skin' => ['normal', 'dry'],
                'concern' => ['shine', 'wear_time', 'finish'],
                'ingredients' => ['vitamin_e', 'keratin'],
                'avoid' => ['acetone'],
            ],
            'tools' => [
                'skin' => ['all_skin_types'],
                'concern' => ['precision', 'ease', 'finish'],
                'ingredients' => ['synthetic_fiber'],
                'avoid' => [],
            ],
            'general' => [
                'skin' => ['normal', 'combination', 'dry'],
                'concern' => ['balance', 'texture', 'comfort'],
                'ingredients' => ['niacinamide', 'glycerin', 'ceramide'],
                'avoid' => ['fragrance'],
            ],
        ];

        $blueprint = $familyBlueprints[$family] ?? $familyBlueprints['general'];

        $concerns = $this->collectTags([
            ...$blueprint['concern'],
            ...$this->detectConcerns($text),
        ]);

        $skinTypes = $this->collectTags([
            ...$blueprint['skin'],
            ...$this->detectSkinTypes($text),
        ]);

        $tones = $this->collectTags([
            $item->shade,
            $item->color,
            $toneRotation[$index % count($toneRotation)],
            ...$this->detectTones($text),
        ]);

        $undertones = $this->collectTags([
            $undertoneRotation[$index % count($undertoneRotation)],
            ...$this->detectUndertones($text),
        ]);

        $ingredients = $this->collectTags([
            ...$blueprint['ingredients'],
            ...$this->detectIngredients($text),
        ]);

        $avoidTags = $this->collectTags([
            ...$blueprint['avoid'],
            ...$this->detectAvoidTags($text),
        ]);

        return [
            $skinTypes,
            $concerns,
            $tones,
            $undertones,
            $ingredients,
            $avoidTags,
        ];
    }

    protected function buildExplanationTemplate(Product $product, KaggleCatalogItem $item, string $family, array $concerns, array $ingredients): string
    {
        $productName = $product->name ?: 'existing product';
        $sourceName = $item->title();
        $brand = $item->brand ?: 'unknown brand';
        $concernSummary = implode(', ', array_slice($concerns, 0, 3));
        $ingredientSummary = implode(', ', array_slice($ingredients, 0, 3));

        return trim(sprintf(
            'Adapted from Kaggle catalog row "%s" by %s into the existing "%s" product. The %s catalog signal emphasizes %s with ingredients such as %s.',
            $sourceName,
            $brand,
            $productName,
            $family,
            $concernSummary ?: 'balanced beauty coverage',
            $ingredientSummary ?: 'general routine support',
        ));
    }

    protected function selectCatalogItem(Collection $items, int $index): KaggleCatalogItem
    {
        $families = $items->groupBy(fn (KaggleCatalogItem $item) => $item->family());
        $familyNames = $families->keys()->values();

        if ($familyNames->isEmpty()) {
            return $items[$index % $items->count()];
        }

        $familyName = $familyNames[$index % $familyNames->count()];
        $familyItems = $families->get($familyName, collect())->values();

        if ($familyItems->isEmpty()) {
            return $items[$index % $items->count()];
        }

        return $familyItems[$index % $familyItems->count()];
    }

    protected function pickValue(array $values, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $values)) {
                continue;
            }

            $value = $values[$key];
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return null;
    }

    protected function normalizeHeader(string $header): string
    {
        $header = preg_replace('/^\xEF\xBB\xBF/', '', $header) ?? $header;
        $header = Str::snake(trim(strtolower($header)));

        return preg_replace('/[^a-z0-9_]+/', '_', $header) ?? $header;
    }

    protected function cleanValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    protected function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($this->cleanValue($value) !== null) {
                return false;
            }
        }

        return true;
    }

    protected function collectTags(array $tags): array
    {
        $normalized = [];

        foreach ($tags as $tag) {
            if ($tag === null) {
                continue;
            }

            $tag = trim(strtolower((string) $tag));
            $tag = str_replace([' ', '-'], '_', $tag);
            $tag = preg_replace('/[^a-z0-9_]+/', '', $tag) ?? $tag;

            if ($tag === '') {
                continue;
            }

            $normalized[$tag] = $tag;
        }

        return array_values($normalized);
    }

    protected function detectConcerns(string $text): array
    {
        return $this->collectKeywordMatches($text, [
            'dark_spot' => ['dark spot', 'brightening', 'vitamin c', 'vitamin-c', 'radiance', 'spot'],
            'dullness' => ['dull', 'glow', 'radiance', 'bright'],
            'dryness' => ['dry', 'dehydrated', 'moistur', 'hydrat'],
            'acne' => ['acne', 'blemish', 'breakout', 'clarify', 'oil control'],
            'texture' => ['texture', 'smooth', 'refine', 'pore'],
            'redness' => ['redness', 'calm', 'soothe', 'sensitive'],
            'oil_control' => ['matte', 'oil', 'shine control'],
            'firmness' => ['firm', 'elastic', 'lift', 'tone'],
        ]);
    }

    protected function detectSkinTypes(string $text): array
    {
        return $this->collectKeywordMatches($text, [
            'oily' => ['oily', 'matte', 'shine control'],
            'dry' => ['dry', 'hydrating', 'moistur', 'cream'],
            'combination' => ['combination', 'balanced'],
            'sensitive' => ['sensitive', 'fragrance-free', 'gentle', 'calm'],
            'normal' => ['normal', 'all skin'],
        ]);
    }

    protected function detectTones(string $text): array
    {
        return $this->collectKeywordMatches($text, [
            'fair' => ['fair', 'ivory'],
            'light' => ['light'],
            'medium' => ['medium', 'beige'],
            'tan' => ['tan', 'golden'],
            'deep' => ['deep', 'rich'],
        ]);
    }

    protected function detectUndertones(string $text): array
    {
        return $this->collectKeywordMatches($text, [
            'warm' => ['warm', 'golden'],
            'cool' => ['cool', 'rosy'],
            'neutral' => ['neutral'],
            'olive' => ['olive'],
        ]);
    }

    protected function detectIngredients(string $text): array
    {
        return $this->collectKeywordMatches($text, [
            'niacinamide' => ['niacinamide'],
            'hyaluronic_acid' => ['hyaluronic acid', 'hyaluronic-acid', 'hyaluron'],
            'salicylic_acid' => ['salicylic acid', 'salicylic-acid', 'bha'],
            'vitamin_c' => ['vitamin c', 'vitamin-c', 'ascorbic'],
            'ceramide' => ['ceramide'],
            'retinol' => ['retinol'],
            'centella' => ['centella', 'cica'],
            'glycerin' => ['glycerin'],
            'squalane' => ['squalane'],
            'shea_butter' => ['shea butter'],
            'peptides' => ['peptide'],
            'keratin' => ['keratin'],
            'argan_oil' => ['argan oil'],
            'mica' => ['mica'],
        ]);
    }

    protected function detectAvoidTags(string $text): array
    {
        return $this->collectKeywordMatches($text, [
            'fragrance' => ['fragrance', 'perfume'],
            'alcohol' => ['alcohol'],
            'essential_oil' => ['essential oil'],
            'sulfates' => ['sulfate'],
            'acetone' => ['acetone'],
            'parabens' => ['paraben'],
        ]);
    }

    protected function collectKeywordMatches(string $text, array $keywordMap): array
    {
        $matches = [];

        foreach ($keywordMap as $tag => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, strtolower($keyword))) {
                    $matches[] = $tag;
                    break;
                }
            }
        }

        return $matches;
    }
}
