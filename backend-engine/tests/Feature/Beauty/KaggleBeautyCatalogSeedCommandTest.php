<?php

namespace Tests\Feature\Beauty;

use App\Domains\Beauty\Models\BeautyProductMapping;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Marvel\Enums\ProductStatus;
use Marvel\Enums\ProductType;
use Tests\TestCase;

class KaggleBeautyCatalogSeedCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_kaggle_seed_command_maps_existing_products_from_csv(): void
    {
        $typeId = DB::table('types')->insertGetId([
            'name' => 'Beauty',
            'slug' => 'beauty',
            'icon' => null,
            'promotional_sliders' => null,
            'images' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (range(1, 4) as $index) {
            DB::table('products')->insert([
                'name' => 'Existing Product ' . $index,
                'slug' => Str::slug('Existing Product ' . $index),
                'description' => 'Catalog item ' . $index,
                'type_id' => $typeId,
                'price' => 10 + $index,
                'sale_price' => null,
                'sku' => 'SKU-' . $index,
                'quantity' => 5,
                'in_stock' => true,
                'is_taxable' => false,
                'shipping_class_id' => null,
                'status' => ProductStatus::PUBLISH,
                'product_type' => ProductType::SIMPLE,
                'unit' => 'piece',
                'height' => null,
                'width' => null,
                'length' => null,
                'image' => null,
                'gallery' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $csvPath = storage_path('app/testing/kaggle-cosmetic-catalog.csv');
        if (!is_dir(dirname($csvPath))) {
            mkdir(dirname($csvPath), 0777, true);
        }

        file_put_contents($csvPath, <<<'CSV'
brand,name,category,description,ingredients,price,image_url,shade,color
GlowLab,Hydra Serum,Skincare,Hydrating serum for dry and sensitive skin,niacinamide; hyaluronic acid,15.99,https://example.test/serum.jpg,Light Beige,warm
Velvet,Soft Matte Foundation,Makeup,Long-wear matte foundation with coverage,vitamin c; mica,24.50,https://example.test/foundation.jpg,Medium Tan,neutral
RootCare,Repair Shampoo,Haircare,Nourishing shampoo for scalp balance,keratin; argan oil,12.00,https://example.test/shampoo.jpg,,cool
PureBody,Daily Lotion,Bodycare,Everyday lotion for smooth hydrated skin,glycerin; ceramide,9.75,https://example.test/lotion.jpg,,warm
CSV);

        $reportPath = storage_path('app/testing/kaggle-import-report.json');

        $this->artisan('beauty:seed-kaggle-catalog', [
            'source' => $csvPath,
            '--limit' => 4,
            '--report-path' => $reportPath,
        ])->assertExitCode(0);

        $this->assertSame(4, BeautyProductMapping::count());
        $this->assertFileExists($reportPath);

        $payload = json_decode((string) file_get_contents($reportPath), true);

        $this->assertSame(4, $payload['products_targeted']);
        $this->assertSame(4, $payload['mappings_written']);
        $this->assertArrayHasKey('skincare', $payload['family_counts']);
        $this->assertArrayHasKey('makeup', $payload['family_counts']);
        $this->assertArrayHasKey('haircare', $payload['family_counts']);
        $this->assertArrayHasKey('bodycare', $payload['family_counts']);
    }
}
