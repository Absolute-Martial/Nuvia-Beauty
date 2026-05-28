<?php

namespace Tests\Feature\Beauty;

use App\Domains\Beauty\Models\BeautyAiTask;
use App\Domains\Beauty\Models\BeautyAnalysisResult;
use App\Domains\Beauty\Models\BeautyProductMapping;
use App\Domains\Beauty\Models\BeautyProfileSnapshot;
use App\Domains\Beauty\Models\BeautyRecommendation;
use App\Domains\Storage\Models\MediaAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Marvel\Database\Models\Product;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\User;
use Marvel\Enums\Permission as PermissionEnum;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PerfectCorpAnalysisFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('queue.default', 'sync');
        Config::set('services.perfect_corp.demo_mode', true);
        Config::set('services.perfect_corp.enabled', false);
    }

    public function test_demo_analysis_start_creates_task_and_completes_without_provider_call(): void
    {
        [$owner, $shop] = $this->createShopOwner();
        $product = $this->createMappedProduct($shop);

        $sessionResponse = $this->createSession($owner, $shop);
        $sessionId = (string) $sessionResponse['data']['session']['id'];

        $mediaAsset = MediaAsset::create([
            'session_id' => $sessionId,
            'shop_id' => $shop->id,
            'owner_type' => 'shop',
            'owner_id' => $shop->id,
            'asset_type' => 'consultation_input_image',
            'storage_provider' => 's3_compatible',
            'disk_name' => 's3_beauty_inputs',
            'bucket' => 'nuvia-private-beauty-inputs',
            'object_key' => 'consultations/demo-image.jpg',
            'content_type' => 'image/jpeg',
            'size_bytes' => 2048,
            'visibility' => 'private',
            'status' => 'confirmed',
        ]);

        Sanctum::actingAs($owner, [], 'sanctum');

        $this->postJson("/api/v1/beauty/sessions/{$sessionId}/attach-media", [
            'media_asset_id' => $mediaAsset->id,
        ])->assertOk();

        $response = $this->postJson("/api/v1/beauty/sessions/{$sessionId}/analysis/start");

        $response->assertStatus(202);
        $response->assertJsonPath('data.task.provider', 'perfect_corp_skin_analysis');
        $response->assertJsonPath('data.task.status', 'completed');
        $response->assertJsonPath('data.analysis_result.summary.demo_mode', true);
        $response->assertJsonMissingPath('data.task.response_payload');

        $taskId = (int) $response->json('data.task.id');

        $this->assertDatabaseHas('beauty_ai_tasks', [
            'id' => $taskId,
            'provider' => 'perfect_corp_skin_analysis',
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('beauty_analysis_results', [
            'beauty_ai_task_id' => $taskId,
            'provider' => 'perfect_corp_skin_analysis',
            'status' => 'completed',
        ]);

        $this->assertGreaterThanOrEqual(
            2,
            BeautyProfileSnapshot::query()->count(),
            'A second profile snapshot should be created from the normalized analysis result.'
        );

        $recommendation = BeautyRecommendation::query()->where('session_id', $sessionId)->first();
        $this->assertNotNull($recommendation, 'Recommendations should be regenerated from the normalized snapshot.');
        $this->assertSame((int) $product->id, (int) $recommendation->product_id);
    }

    public function test_analysis_status_endpoint_returns_normalized_result_only(): void
    {
        [$owner, $shop] = $this->createShopOwner();
        $this->createMappedProduct($shop);

        $sessionResponse = $this->createSession($owner, $shop);
        $sessionId = (string) $sessionResponse['data']['session']['id'];

        $mediaAsset = MediaAsset::create([
            'session_id' => $sessionId,
            'shop_id' => $shop->id,
            'owner_type' => 'shop',
            'owner_id' => $shop->id,
            'asset_type' => 'consultation_input_image',
            'storage_provider' => 's3_compatible',
            'disk_name' => 's3_beauty_inputs',
            'bucket' => 'nuvia-private-beauty-inputs',
            'object_key' => 'consultations/demo-image-2.jpg',
            'content_type' => 'image/jpeg',
            'size_bytes' => 4096,
            'visibility' => 'private',
            'status' => 'confirmed',
        ]);

        Sanctum::actingAs($owner, [], 'sanctum');

        $this->postJson("/api/v1/beauty/sessions/{$sessionId}/attach-media", [
            'media_asset_id' => $mediaAsset->id,
        ])->assertOk();

        $taskId = (int) $this->postJson("/api/v1/beauty/sessions/{$sessionId}/analysis/start")
            ->assertStatus(202)
            ->json('data.task.id');

        $statusResponse = $this->getJson("/api/v1/beauty/analysis/{$taskId}/status");

        $statusResponse->assertOk();
        $statusResponse->assertJsonPath('data.task.id', $taskId);
        $statusResponse->assertJsonPath('data.task.status', 'completed');
        $statusResponse->assertJsonPath('data.analysis_result.summary.demo_mode', true);
        $statusResponse->assertJsonMissingPath('data.analysis_result.raw_payload');
        $statusResponse->assertJsonMissingPath('data.task.response_payload');

        $this->assertNotEmpty($statusResponse->json('data.recommendations'));
    }

    protected function createShopOwner(): array
    {
        $owner = User::query()->create([
            'name' => 'Vendor Owner',
            'email' => 'vendor-owner@example.test',
            'password' => bcrypt('password'),
        ]);
        $owner->forceFill(['email_verified_at' => now()])->save();

        Permission::findOrCreate(PermissionEnum::STORE_OWNER, 'api');
        $owner->givePermissionTo(PermissionEnum::STORE_OWNER);

        $shop = Shop::query()->create([
            'owner_id' => $owner->id,
            'name' => 'Radiant Beauty',
            'slug' => 'radiant-beauty',
            'is_active' => true,
        ]);

        return [$owner, $shop];
    }

    protected function createMappedProduct(Shop $shop): Product
    {
        $typeId = DB::table('types')->insertGetId([
            'name' => 'Skincare',
            'slug' => 'skincare',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $product = Product::query()->create([
            'name' => 'Niacinamide Bright Serum',
            'slug' => 'niacinamide-bright-serum',
            'type_id' => $typeId,
            'price' => 28,
            'quantity' => 25,
            'unit' => 'bottle',
            'shop_id' => $shop->id,
        ]);

        BeautyProductMapping::query()->create([
            'product_id' => $product->id,
            'concern_tags' => ['dark_spot'],
            'skin_type_tags' => ['combination'],
            'tone_tags' => ['medium'],
            'undertone_tags' => ['neutral'],
            'ingredient_tags' => ['niacinamide'],
            'avoid_tags' => ['fragrance'],
            'explanation_template' => 'Demo mapped serum for Phase 5.',
        ]);

        return $product;
    }

    protected function createSession(User $owner, Shop $shop): array
    {
        Sanctum::actingAs($owner, [], 'sanctum');

        return $this->postJson('/api/v1/beauty/sessions', [
            'shop_id' => $shop->id,
            'consultation_mode' => 'guest',
            'customer_name' => 'Demo Guest',
            'contact_email' => 'guest@example.test',
            'skin_type_tags' => ['combination'],
            'tone_tags' => ['medium'],
            'undertone_tags' => ['neutral'],
            'concern_tags' => ['dark_spot'],
            'ingredient_tags' => ['niacinamide'],
            'avoid_tags' => ['fragrance'],
        ])->assertCreated()->json();
    }
}
