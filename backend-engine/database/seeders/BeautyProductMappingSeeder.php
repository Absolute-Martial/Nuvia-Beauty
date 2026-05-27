<?php

namespace Database\Seeders;

use App\Domains\Beauty\Models\BeautyProductMapping;
use Illuminate\Database\Seeder;
use Marvel\Database\Models\Product;

class BeautyProductMappingSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()
            ->orderBy('id')
            ->limit(10)
            ->get();

        if ($products->isEmpty()) {
            $this->command?->warn('BeautyProductMappingSeeder skipped: no products are available to map.');
            return;
        }

        $presets = [
            [
                'concern_tags' => ['dark-spot', 'texture'],
                'skin_type_tags' => ['oily', 'combination'],
                'tone_tags' => ['medium', 'tan'],
                'undertone_tags' => ['warm', 'neutral'],
                'ingredient_tags' => ['niacinamide', 'vitamin-c'],
                'avoid_tags' => ['fragrance'],
                'explanation_template' => 'Supports brightening and texture-balancing needs.',
            ],
            [
                'concern_tags' => ['dryness', 'dehydration'],
                'skin_type_tags' => ['dry', 'normal'],
                'tone_tags' => ['light', 'medium'],
                'undertone_tags' => ['neutral', 'cool'],
                'ingredient_tags' => ['hyaluronic-acid', 'ceramide'],
                'avoid_tags' => ['alcohol'],
                'explanation_template' => 'Focuses on barrier support and moisture retention.',
            ],
            [
                'concern_tags' => ['acne', 'texture'],
                'skin_type_tags' => ['oily', 'sensitive'],
                'tone_tags' => ['fair', 'light'],
                'undertone_tags' => ['cool', 'neutral'],
                'ingredient_tags' => ['salicylic-acid', 'niacinamide'],
                'avoid_tags' => ['fragrance'],
                'explanation_template' => 'Designed for congestion-prone routines without diagnosis language.',
            ],
            [
                'concern_tags' => ['dullness', 'dark-spot'],
                'skin_type_tags' => ['normal', 'combination'],
                'tone_tags' => ['medium', 'deep'],
                'undertone_tags' => ['warm', 'olive'],
                'ingredient_tags' => ['vitamin-c', 'licorice-root'],
                'avoid_tags' => ['essential-oil'],
                'explanation_template' => 'Supports a brighter finish for uneven-looking tone.',
            ],
            [
                'concern_tags' => ['redness', 'sensitivity'],
                'skin_type_tags' => ['sensitive', 'dry'],
                'tone_tags' => ['fair', 'light'],
                'undertone_tags' => ['cool'],
                'ingredient_tags' => ['centella', 'ceramide'],
                'avoid_tags' => ['fragrance', 'retinol'],
                'explanation_template' => 'Favors calming ingredients for easily-reactive routines.',
            ],
        ];

        foreach ($products as $index => $product) {
            $preset = $presets[$index % count($presets)];

            BeautyProductMapping::updateOrCreate(
                ['product_id' => $product->id],
                $preset
            );
        }

        $this->command?->info('BeautyProductMappingSeeder completed for the first 10 available products.');
    }
}
