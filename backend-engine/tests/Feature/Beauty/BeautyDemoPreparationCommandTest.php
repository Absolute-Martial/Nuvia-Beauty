<?php

namespace Tests\Feature\Beauty;

use App\Domains\Beauty\Models\BeautyAnalysisResult;
use App\Domains\Beauty\Models\BeautyProductMapping;
use App\Domains\Beauty\Models\BeautyRecommendation;
use App\Domains\Beauty\Models\BeautySession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Marvel\Enums\ProductStatus;
use Marvel\Enums\ProductType;
use Marvel\Enums\Role as UserRole;
use Marvel\Enums\Permission as PermissionEnum;
use RuntimeException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BeautyDemoPreparationCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        File::ensureDirectoryExists(storage_path('app/testing'));
    }

    public function test_demo_preparation_command_builds_repeatable_demo_records(): void
    {
        $ownerId = DB::table('users')->insertGetId([
            'name' => 'Demo Owner',
            'email' => 'owner@example.test',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $shopId = DB::table('shops')->insertGetId([
            'owner_id' => $ownerId,
            'name' => 'Demo Shop',
            'slug' => 'demo-shop',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->where('id', $ownerId)->update(['shop_id' => $shopId]);

        $typeId = DB::table('types')->insertGetId([
            'name' => 'Beauty',
            'slug' => 'beauty',
            'icon' => null,
            'promotional_sliders' => null,
            'images' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (range(1, 12) as $index) {
            DB::table('products')->insert([
                'name' => 'Demo Product ' . $index,
                'slug' => Str::slug('Demo Product ' . $index),
                'description' => 'Demo catalog product ' . $index,
                'type_id' => $typeId,
                'price' => 20 + $index,
                'sale_price' => null,
                'shop_id' => $shopId,
                'sku' => 'DEMO-' . $index,
                'quantity' => 10,
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

        $reportPath = storage_path('app/testing/beauty-demo-preparation-report.json');

        $this->artisan('beauty:prepare-demo', [
            '--shop-id' => $shopId,
            '--consultant-user-id' => $ownerId,
            '--report-path' => $reportPath,
        ])->assertExitCode(0);

        $this->assertSame(10, BeautyProductMapping::query()->whereHas('product', fn ($query) => $query->where('shop_id', $shopId))->count());
        $this->assertFileExists($reportPath);

        $session = BeautySession::query()->where('public_id', 'demo-seller-consultation')->first();
        $this->assertNotNull($session);
        $this->assertSame(BeautySession::STATE_SAVED, $session->session_state);

        $analysisResult = BeautyAnalysisResult::query()->where('beauty_session_id', $session->id)->latest('id')->first();
        $this->assertNotNull($analysisResult);
        $this->assertSame('completed', $analysisResult->status);

        $recommendations = BeautyRecommendation::query()
            ->where('session_id', 'demo-seller-consultation')
            ->orderByDesc('score')
            ->get();

        $this->assertGreaterThanOrEqual(4, $recommendations->count());
        $this->assertGreaterThanOrEqual(3, $recommendations->where('score', '>=', 80)->count());
        $this->assertGreaterThanOrEqual(1, $recommendations->filter(fn ($recommendation) => !empty($recommendation->warnings_json))->count());
    }

    public function test_demo_preparation_command_creates_a_deterministic_demo_catalog_when_database_is_empty(): void
    {
        $reportPath = storage_path('app/testing/beauty-demo-empty-db-report.json');

        $this->artisan('beauty:prepare-demo', [
            '--report-path' => $reportPath,
        ])->assertExitCode(0);

        $this->assertFileExists($reportPath);

        $shop = DB::table('shops')->where('slug', 'nuvia-demo-beauty')->first();
        $this->assertNotNull($shop);

        $owner = DB::table('users')->where('email', 'demo-owner@nuvia.local')->first();
        $this->assertNotNull($owner);
        $this->assertSame((int) $owner->id, (int) $shop->owner_id);

        $this->assertSame(
            10,
            DB::table('products')
                ->where('shop_id', $shop->id)
                ->where('status', ProductStatus::PUBLISH)
                ->count()
        );
        $this->assertDatabaseHas('products', [
            'shop_id' => $shop->id,
            'name' => 'Studio Moisture Cream',
        ]);

        $session = BeautySession::query()->where('public_id', 'demo-seller-consultation')->first();
        $this->assertNotNull($session);
        $this->assertSame((int) $shop->id, (int) $session->shop_id);
        $this->assertSame(BeautySession::STATE_SAVED, $session->session_state);

        $report = json_decode((string) file_get_contents($reportPath), true);
        $this->assertIsArray($report['catalog_sources'] ?? null);
        $this->assertNotEmpty($report['catalog_sources'] ?? []);
    }

    public function test_demo_audit_command_reports_success_after_preparation(): void
    {
        $ownerId = DB::table('users')->insertGetId([
            'name' => 'Audit Owner',
            'email' => 'audit-owner@example.test',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $shopId = DB::table('shops')->insertGetId([
            'owner_id' => $ownerId,
            'name' => 'Audit Shop',
            'slug' => 'audit-shop',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->where('id', $ownerId)->update(['shop_id' => $shopId]);

        $typeId = DB::table('types')->insertGetId([
            'name' => 'Beauty',
            'slug' => 'beauty-audit',
            'icon' => null,
            'promotional_sliders' => null,
            'images' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (range(1, 10) as $index) {
            DB::table('products')->insert([
                'name' => 'Audit Product ' . $index,
                'slug' => Str::slug('Audit Product ' . $index),
                'description' => 'Audit product ' . $index,
                'type_id' => $typeId,
                'price' => 30 + $index,
                'sale_price' => null,
                'shop_id' => $shopId,
                'sku' => 'AUDIT-' . $index,
                'quantity' => 8,
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

        $this->artisan('beauty:prepare-demo', [
            '--shop-id' => $shopId,
            '--consultant-user-id' => $ownerId,
        ])->assertExitCode(0);

        Config::set('filesystems.storage_contract', 'local');
        Config::set('filesystems.disks.s3_beauty_inputs.bucket', null);
        Config::set('filesystems.disks.s3_beauty_results.bucket', null);

        $auditPath = storage_path('app/testing/beauty-demo-audit-report.json');

        $this->artisan('beauty:audit-demo-readiness', [
            '--shop-id' => $shopId,
            '--report-path' => $auditPath,
        ])->assertExitCode(0);

        $this->assertFileExists($auditPath);
    }

    public function test_demo_preparation_command_is_blocked_in_production_by_default(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Phase 6 demo preparation is blocked in production.');

        $this->artisan('beauty:prepare-demo');
    }

    public function test_demo_preparation_and_audit_can_run_in_production_when_explicitly_allowed(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        Config::set('services.beauty_demo.allow_production', true);

        $reportPath = storage_path('app/testing/beauty-demo-production-report.json');
        $auditPath = storage_path('app/testing/beauty-demo-production-audit-report.json');

        $this->artisan('beauty:prepare-demo', [
            '--report-path' => $reportPath,
            '--allow-production' => true,
        ])->assertExitCode(0);

        $this->artisan('beauty:audit-demo-readiness', [
            '--report-path' => $auditPath,
            '--allow-production' => true,
        ])->assertExitCode(0);

        $this->assertFileExists($reportPath);
        $this->assertFileExists($auditPath);
    }

    public function test_demo_preparation_uses_configured_demo_credentials_and_bootstraps_demo_admin(): void
    {
        Config::set('services.beauty_demo.owner_password', 'DemoOwnerPass123!');
        Config::set('services.beauty_demo.staff_password', 'DemoStaffPass123!');
        Config::set('services.beauty_demo.admin_email', 'demo-admin@example.test');
        Config::set('services.beauty_demo.admin_password', 'DemoAdminPass123!');

        $this->artisan('beauty:prepare-demo')->assertExitCode(0);

        $owner = DB::table('users')->where('email', 'demo-owner@nuvia.local')->first();
        $staff = DB::table('users')->where('email', 'demo-seller@nuvia.local')->first();
        $admin = DB::table('users')->where('email', 'demo-admin@example.test')->first();

        $this->assertNotNull($owner);
        $this->assertNotNull($staff);
        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check('DemoOwnerPass123!', (string) $owner->password));
        $this->assertTrue(Hash::check('DemoStaffPass123!', (string) $staff->password));
        $this->assertTrue(Hash::check('DemoAdminPass123!', (string) $admin->password));

        $adminModel = \Marvel\Database\Models\User::query()->findOrFail($admin->id);
        $this->assertTrue($adminModel->safeHasPermissionTo(PermissionEnum::SUPER_ADMIN));
    }
}
