<?php

namespace App\Domains\Beauty\Services;

use App\Domains\Beauty\Models\BeautyAiTask;
use App\Domains\Beauty\Models\BeautyAnalysisResult;
use App\Domains\Beauty\Models\BeautyProductMapping;
use App\Domains\Beauty\Models\BeautyProfile;
use App\Domains\Beauty\Models\BeautyRecommendation;
use App\Domains\Beauty\Models\BeautySession;
use App\Domains\Storage\Models\MediaAsset;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Marvel\Database\Models\Product;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\Type;
use Marvel\Database\Models\User;
use Marvel\Enums\Permission as UserPermission;
use Marvel\Enums\ProductType;
use Marvel\Enums\ProductStatus;
use Marvel\Enums\Role as UserRole;
use RuntimeException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class BeautyDemoPreparationService
{
    public const DEMO_SESSION_PUBLIC_ID = 'demo-seller-consultation';
    public const DEMO_MEDIA_OBJECT_KEY = 'demo/beauty-consultations/demo-seller-consultation-input.png';
    public const DEMO_CUSTOMER_NAME = 'Nuvia Demo Guest';
    public const DEMO_CUSTOMER_EMAIL = 'demo-guest@nuvia.local';
    public const DEMO_CUSTOMER_PHONE = '+1-555-0106';
    public const DEMO_OWNER_EMAIL = 'demo-owner@nuvia.local';
    public const DEMO_ADMIN_EMAIL = 'demo-admin@nuvia.local';
    public const DEMO_SHOP_SLUG = 'nuvia-demo-beauty';
    public const DEMO_TYPE_SLUG = 'beauty-demo';

    public function __construct(
        protected BeautySessionService $sessions,
        protected PerfectCorpTaskService $perfectCorpTasks,
        protected KaggleBeautyCatalogImportService $kaggleImporter,
        protected FeatureExtractorService $features,
    ) {
    }

    public function prepare(array $options = []): array
    {
        $this->assertDemoExecutionAllowed($options);

        $shop = $this->resolveShop($options['shop_id'] ?? null, true, true);
        $consultant = $this->resolveConsultant($shop, $options['consultant_user_id'] ?? null);
        $demoStaff = $this->ensureDemoStaffAccount($shop);
        $demoAdmin = $this->resolveOrCreateDemoAdmin();
        $source = isset($options['kaggle_source']) ? trim((string) $options['kaggle_source']) : '';
        $mappingLimit = max(10, (int) ($options['mapping_limit'] ?? 40));
        $reportPath = (string) ($options['report_path'] ?? storage_path('app/beauty/demo-preparation-report.json'));

        $importSummary = null;
        if ($source !== '') {
            $importSummary = $this->kaggleImporter->import($source, $mappingLimit, false);
        }

        $products = $this->ensurePublishedDemoCatalog($shop);

        $mappingSummary = $this->upsertShowcaseMappings($products);
        $this->cleanupPreviousDemoArtifacts($shop->id);
        $sessionSummary = $this->createDemoSession($shop, $consultant);

        $summary = [
            'prepared_at' => now()->toIso8601String(),
            'environment' => app()->environment(),
            'shop' => [
                'id' => (int) $shop->id,
                'name' => $shop->name,
                'slug' => $shop->slug,
            ],
            'consultant' => [
                'id' => (int) $consultant->id,
                'name' => $consultant->name,
                'email' => $consultant->email,
            ],
            'demo_mode' => (bool) config('services.perfect_corp.demo_mode', true),
            'catalog_sources' => $this->catalogSources(),
            'demo_accounts' => array_values(array_filter([
                [
                    'role' => 'store_owner',
                    'email' => $consultant->email,
                ],
                [
                    'role' => 'staff',
                    'email' => $demoStaff?->email,
                ],
                [
                    'role' => 'super_admin',
                    'email' => $demoAdmin?->email,
                ],
            ], fn (array $account) => !empty($account['email']))),
            'kaggle_import' => $importSummary,
            'mapping_summary' => $mappingSummary,
            'session_summary' => $sessionSummary,
        ];

        $this->writeReport($summary, $reportPath);
        $summary['report_path'] = $reportPath;

        return $summary;
    }

    public function audit(array $options = []): array
    {
        $shop = $this->resolveShop($options['shop_id'] ?? null, false);
        $reportPath = (string) ($options['report_path'] ?? storage_path('app/beauty/demo-readiness-audit.json'));
        $demoSession = BeautySession::query()
            ->with(['analysisResults', 'primaryMediaAsset', 'shop'])
            ->where('public_id', self::DEMO_SESSION_PUBLIC_ID)
            ->first();

        $checks = collect([
            $this->check(
                'non_production_environment',
                !app()->environment('production') || $this->allowsProductionDemoOperations($options),
                'Demo tooling is blocked in production unless BEAUTY_DEMO_ALLOW_PRODUCTION=true or --allow-production is provided.',
                [
                    'app_env' => app()->environment(),
                    'allow_production' => $this->allowsProductionDemoOperations($options),
                ],
            ),
            $this->check('perfect_corp_demo_mode', (bool) config('services.perfect_corp.demo_mode', true), 'PERFECT_CORP_DEMO_MODE should stay enabled for the Phase 6 demo.'),
            $this->check('beauty_seed_command_present', $this->artisanCommandExists('beauty:seed-kaggle-catalog'), 'Kaggle-backed beauty mapping import command should be registered.'),
            $this->check('beauty_demo_prepare_command_present', $this->artisanCommandExists('beauty:prepare-demo'), 'Phase 6 demo preparation command should be registered.'),
            $this->check('beauty_demo_audit_command_present', $this->artisanCommandExists('beauty:audit-demo-readiness'), 'Phase 6 demo audit command should be registered.'),
            $this->check('required_beauty_routes_present', $this->requiredRoutesPresent(), 'Required Phase 4/5 beauty routes should be registered.'),
            $this->check('shop_with_demo_catalog', $shop !== null, 'A shop with at least 10 published products is required.'),
            $this->check('demo_session_exists', $demoSession !== null, 'Run beauty:prepare-demo to create the deterministic seller consultation.'),
            $this->beautyStorageCheck(),
            $this->check('queue_strategy_documented', in_array((string) config('queue.default'), ['sync', 'database', 'redis'], true), 'Queue driver should be explicitly configured.'),
        ]);

        if ($shop) {
            $mappedProducts = BeautyProductMapping::query()
                ->whereHas('product', fn ($query) => $query->where('shop_id', $shop->id))
                ->count();
            $readyMappings = BeautyProductMapping::query()
                ->whereHas('product', fn ($query) => $query->where('shop_id', $shop->id))
                ->get()
                ->filter(fn (BeautyProductMapping $mapping) => $mapping->recommendationStatus() === BeautyProductMapping::STATUS_READY)
                ->count();

            $checks->push(
                $this->check('minimum_demo_mappings', $mappedProducts >= 10, 'The demo shop needs at least 10 mapped products.', ['mapped_products' => $mappedProducts]),
                $this->check('ready_demo_mappings', $readyMappings >= 4, 'The curated demo should surface several recommendation-ready products.', ['ready_mappings' => $readyMappings]),
            );
        }

        if ($demoSession) {
            $recommendations = BeautyRecommendation::query()
                ->where('session_id', $demoSession->public_id)
                ->orderByDesc('score')
                ->get();

            $strongRecommendations = $recommendations->filter(fn (BeautyRecommendation $recommendation) => (int) $recommendation->score >= 80)->count();
            $warningRecommendations = $recommendations->filter(fn (BeautyRecommendation $recommendation) => !empty($recommendation->warnings_json))->count();
            $completedAnalysis = $demoSession->analysisResults->contains(fn (BeautyAnalysisResult $result) => $result->status === 'completed');
            $privateMediaReady = $demoSession->primaryMediaAsset
                && $demoSession->primaryMediaAsset->visibility === 'private'
                && $demoSession->primaryMediaAsset->status === 'confirmed';

            $checks->push(
                $this->check('demo_session_saved', $demoSession->session_state === BeautySession::STATE_SAVED, 'The demo consultation should finish in the saved state.', ['session_state' => $demoSession->session_state]),
                $this->check('demo_media_private', $privateMediaReady, 'The demo consultation media asset must be confirmed and private.'),
                $this->check('demo_analysis_completed', $completedAnalysis, 'A completed demo analysis result is required for Phase 6.'),
                $this->check('three_strong_recommendations', $strongRecommendations >= 3, 'The demo should produce at least 3 strong recommendations.', ['strong_recommendations' => $strongRecommendations]),
                $this->check('warning_recommendation_present', $warningRecommendations >= 1, 'The demo should include at least 1 avoid-tag warning example.', ['warning_recommendations' => $warningRecommendations]),
            );
        }

        $summary = [
            'checked_at' => now()->toIso8601String(),
            'environment' => app()->environment(),
            'queue_driver' => (string) config('queue.default'),
            'shop' => $shop ? ['id' => (int) $shop->id, 'name' => $shop->name, 'slug' => $shop->slug] : null,
            'demo_session_public_id' => self::DEMO_SESSION_PUBLIC_ID,
            'passed' => $checks->every(fn (array $check) => $check['passed']),
            'checks' => $checks->values()->all(),
        ];

        $this->writeReport($summary, $reportPath);
        $summary['report_path'] = $reportPath;

        return $summary;
    }

    protected function createDemoSession(Shop $shop, User $consultant): array
    {
        $payload = $this->demoSessionPayload($shop->id);
        $session = $this->sessions->createSession($payload, $consultant);
        $session->forceFill(['public_id' => self::DEMO_SESSION_PUBLIC_ID])->save();
        $session->refresh();

        $mediaAsset = MediaAsset::updateOrCreate(
            [
                'session_id' => self::DEMO_SESSION_PUBLIC_ID,
                'object_key' => self::DEMO_MEDIA_OBJECT_KEY,
            ],
            [
                'profile_id' => $session->beauty_profile_id,
                'shop_id' => $shop->id,
                'owner_type' => 'shop',
                'owner_id' => $shop->id,
                'asset_type' => 'consultation_input_image',
                'storage_provider' => (string) config('filesystems.disks.s3_beauty_inputs.driver', 's3'),
                'disk_name' => 's3_beauty_inputs',
                'bucket' => (string) config('filesystems.disks.s3_beauty_inputs.bucket'),
                'object_version' => null,
                'content_type' => 'image/png',
                'size_bytes' => 1254,
                'checksum_sha256' => hash('sha256', self::DEMO_SESSION_PUBLIC_ID),
                'visibility' => 'private',
                'status' => 'confirmed',
                'expires_at' => null,
            ],
        );

        $session = $this->sessions->attachMedia(self::DEMO_SESSION_PUBLIC_ID, (int) $mediaAsset->id, $consultant);
        $analysisPayload = $this->sessions->startAnalysis(self::DEMO_SESSION_PUBLIC_ID, $consultant);
        $task = BeautyAiTask::findOrFail((int) $analysisPayload['task']->id);

        if ($task->status !== 'completed') {
            $this->perfectCorpTasks->processCreateTask((int) $task->id);
            $task = BeautyAiTask::findOrFail((int) $task->id);
        }

        if ($task->status !== 'completed') {
            throw new RuntimeException('The demo analysis task did not complete successfully.');
        }

        $analysisPayload = $this->sessions->analysisStatus((int) $task->id, $consultant);
        $acceptedRecommendationIds = BeautyRecommendation::query()
            ->where('session_id', self::DEMO_SESSION_PUBLIC_ID)
            ->orderByDesc('score')
            ->limit(3)
            ->pluck('id')
            ->all();

        $savedSession = $this->sessions->saveSession(self::DEMO_SESSION_PUBLIC_ID, $consultant, [
            'notes' => 'Prepared by Phase 6 demo tooling.',
            'accepted_recommendation_ids' => $acceptedRecommendationIds,
        ]);

        return [
            'public_id' => $savedSession->public_id,
            'session_state' => $savedSession->session_state,
            'media_asset_id' => (int) $mediaAsset->id,
            'analysis_task_id' => (int) $task->id,
            'analysis_status' => $task->status,
            'recommendation_count' => count($analysisPayload['recommendations'] ?? []),
            'strong_recommendations' => collect($analysisPayload['recommendations'] ?? [])->where('score', '>=', 80)->count(),
            'warning_recommendations' => collect($analysisPayload['recommendations'] ?? [])
                ->filter(fn (array $recommendation) => !empty($recommendation['warnings']))
                ->count(),
        ];
    }

    protected function upsertShowcaseMappings(Collection $products): array
    {
        $templates = $this->showcaseTemplates();
        $records = [];

        foreach ($products->values() as $index => $product) {
            $template = $templates[$index] ?? $templates[array_key_last($templates)];
            $payload = $this->features->normalizeMapping($template);
            $payload['explanation_template'] = $template['explanation_template'];

            $mapping = BeautyProductMapping::updateOrCreate(
                ['product_id' => $product->id],
                $payload,
            );

            $records[] = [
                'product_id' => (int) $product->id,
                'product_name' => $product->name,
                'status' => $mapping->recommendationStatus(),
                'avoid_tags' => $mapping->avoid_tags ?? [],
            ];
        }

        return [
            'products_targeted' => count($records),
            'ready_for_recommendation' => collect($records)->where('status', BeautyProductMapping::STATUS_READY)->count(),
            'records' => $records,
        ];
    }

    protected function showcaseTemplates(): array
    {
        return [
            [
                'skin_type_tags' => ['oily'],
                'tone_tags' => ['medium'],
                'undertone_tags' => ['warm'],
                'concern_tags' => ['dark_spots', 'hydration'],
                'ingredient_tags' => ['niacinamide', 'hyaluronic_acid'],
                'avoid_tags' => [],
                'explanation_template' => 'Strong match for oily skin with dark-spot and hydration focus.',
            ],
            [
                'skin_type_tags' => ['oily'],
                'tone_tags' => ['medium'],
                'undertone_tags' => ['warm'],
                'concern_tags' => ['acne', 'dark_spots'],
                'ingredient_tags' => ['salicylic_acid', 'niacinamide'],
                'avoid_tags' => [],
                'explanation_template' => 'Strong match for acne support and tone correction.',
            ],
            [
                'skin_type_tags' => ['oily'],
                'tone_tags' => ['medium'],
                'undertone_tags' => ['warm'],
                'concern_tags' => ['hydration', 'acne'],
                'ingredient_tags' => ['hyaluronic_acid', 'salicylic_acid'],
                'avoid_tags' => [],
                'explanation_template' => 'Strong match for balanced hydration and breakout-prone skin.',
            ],
            [
                'skin_type_tags' => ['oily'],
                'tone_tags' => ['medium'],
                'undertone_tags' => ['warm'],
                'concern_tags' => ['dark_spots', 'hydration'],
                'ingredient_tags' => ['niacinamide', 'hyaluronic_acid'],
                'avoid_tags' => ['fragrance'],
                'explanation_template' => 'Intentional warning example with fragrance sensitivity overlap.',
            ],
            [
                'skin_type_tags' => ['dry'],
                'tone_tags' => ['fair'],
                'undertone_tags' => ['cool'],
                'concern_tags' => ['dullness'],
                'ingredient_tags' => ['vitamin_c'],
                'avoid_tags' => ['essential_oil'],
                'explanation_template' => 'Lower-score contrasting profile for demo variety.',
            ],
            [
                'skin_type_tags' => ['combination'],
                'tone_tags' => ['deep'],
                'undertone_tags' => ['neutral'],
                'concern_tags' => ['texture'],
                'ingredient_tags' => ['ceramide'],
                'avoid_tags' => ['alcohol'],
                'explanation_template' => 'Lower-score combination-skin fallback mapping.',
            ],
            [
                'skin_type_tags' => ['sensitive'],
                'tone_tags' => ['tan'],
                'undertone_tags' => ['olive'],
                'concern_tags' => ['redness'],
                'ingredient_tags' => ['peptides'],
                'avoid_tags' => ['fragrance'],
                'explanation_template' => 'Sensitive-skin contrast mapping for UI variety.',
            ],
            [
                'skin_type_tags' => ['normal'],
                'tone_tags' => ['deep'],
                'undertone_tags' => ['cool'],
                'concern_tags' => ['firmness'],
                'ingredient_tags' => ['retinol'],
                'avoid_tags' => [],
                'explanation_template' => 'General contrast mapping for non-targeted products.',
            ],
            [
                'skin_type_tags' => ['dry'],
                'tone_tags' => ['light'],
                'undertone_tags' => ['neutral'],
                'concern_tags' => ['dryness'],
                'ingredient_tags' => ['glycerin'],
                'avoid_tags' => ['alcohol'],
                'explanation_template' => 'Dry-skin support mapping outside the primary demo persona.',
            ],
            [
                'skin_type_tags' => ['combination'],
                'tone_tags' => ['fair'],
                'undertone_tags' => ['warm'],
                'concern_tags' => ['uneven_tone'],
                'ingredient_tags' => ['vitamin_c'],
                'avoid_tags' => [],
                'explanation_template' => 'Final contrast mapping to round out the 10-product showcase.',
            ],
        ];
    }

    protected function demoSessionPayload(int $shopId): array
    {
        return [
            'shop_id' => $shopId,
            'consultation_mode' => 'guest',
            'customer_name' => self::DEMO_CUSTOMER_NAME,
            'contact_email' => self::DEMO_CUSTOMER_EMAIL,
            'contact_phone' => self::DEMO_CUSTOMER_PHONE,
            'skin_type_tags' => ['oily'],
            'tone_tags' => ['medium'],
            'undertone_tags' => ['warm'],
            'concern_tags' => ['dark_spots', 'acne', 'hydration'],
            'ingredient_tags' => ['niacinamide', 'hyaluronic_acid', 'salicylic_acid'],
            'avoid_tags' => ['fragrance'],
            'notes' => 'Prepared by Phase 6 demo tooling.',
        ];
    }

    protected function resolveShop(?int $shopId, bool $requireMinimumCatalog = true, bool $seedDemoCatalog = false): ?Shop
    {
        if ($shopId) {
            $shop = Shop::find($shopId);
            if (!$shop) {
                throw new RuntimeException('The requested demo shop could not be found.');
            }

            if ($seedDemoCatalog) {
                $this->ensurePublishedDemoCatalog($shop);
            }

            if ($requireMinimumCatalog && $this->publishedProductsForShop($shop)->count() < 10) {
                throw new RuntimeException('The requested demo shop does not have at least 10 published products.');
            }

            return $shop;
        }

        $demoShop = Shop::query()
            ->where('slug', self::DEMO_SHOP_SLUG)
            ->first();

        if ($demoShop) {
            if ($seedDemoCatalog) {
                $this->ensurePublishedDemoCatalog($demoShop);
            }

            if ($requireMinimumCatalog && $this->publishedProductsForShop($demoShop)->count() < 10) {
                throw new RuntimeException('The dedicated Phase 6 demo shop does not have at least 10 published products.');
            }

            return $demoShop;
        }

        if ($seedDemoCatalog) {
            $shop = $this->resolveOrCreateDemoShop();
            $this->ensurePublishedDemoCatalog($shop);

            return $shop->fresh();
        }

        $shop = Shop::query()
            ->withCount(['products' => fn ($query) => $query->where('status', ProductStatus::PUBLISH)])
            ->orderByDesc('products_count')
            ->first();

        if (!$shop) {
            return null;
        }

        if ($requireMinimumCatalog && (int) $shop->products_count < 10) {
            throw new RuntimeException('Phase 6 demo prep requires at least one shop with 10 published products.');
        }

        return $shop;
    }

    protected function resolveConsultant(Shop $shop, ?int $consultantUserId): User
    {
        $this->ensurePermissionGraph();

        if ($consultantUserId) {
            $user = User::find($consultantUserId);
            if (!$user) {
                throw new RuntimeException('The requested consultant user could not be found.');
            }

            $this->grantShopAccess($user, $shop);

            return $user;
        }

        if ($shop->owner) {
            $this->grantStoreOwnerAccess($shop->owner);
            return $shop->owner;
        }

        $staff = User::query()->where('shop_id', $shop->id)->first();
        if ($staff) {
            $this->grantStaffAccess($staff, $shop);
            return $staff;
        }

        $superAdmin = User::permission(UserPermission::SUPER_ADMIN)->first();
        if ($superAdmin) {
            $this->grantSuperAdminAccess($superAdmin);
            return $superAdmin;
        }

        return $this->createDemoStaff($shop);
    }

    protected function grantShopAccess(User $user, Shop $shop): void
    {
        if ((int) $shop->owner_id === (int) $user->id) {
            $this->grantStoreOwnerAccess($user);
            return;
        }

        if ($user->shop_id === null || (int) $user->shop_id !== (int) $shop->id) {
            $user->forceFill(['shop_id' => $shop->id])->save();
        }

        $this->grantStaffAccess($user, $shop);
    }

    protected function grantStoreOwnerAccess(User $user): void
    {
        $user->givePermissionTo([UserPermission::STORE_OWNER, UserPermission::CUSTOMER]);
        if (!$user->hasRole(UserRole::STORE_OWNER)) {
            $user->assignRole(UserRole::STORE_OWNER);
        }
    }

    protected function grantStaffAccess(User $user, Shop $shop): void
    {
        if ($user->shop_id === null || (int) $user->shop_id !== (int) $shop->id) {
            $user->forceFill(['shop_id' => $shop->id])->save();
        }

        $user->givePermissionTo([UserPermission::STAFF, UserPermission::CUSTOMER]);
        if (!$user->hasRole(UserRole::STAFF)) {
            $user->assignRole(UserRole::STAFF);
        }
    }

    protected function grantSuperAdminAccess(User $user): void
    {
        $user->givePermissionTo([
            UserPermission::SUPER_ADMIN,
            UserPermission::STORE_OWNER,
            UserPermission::CUSTOMER,
        ]);

        if (!$user->hasRole(UserRole::SUPER_ADMIN)) {
            $user->assignRole(UserRole::SUPER_ADMIN);
        }
    }

    protected function createDemoStaff(Shop $shop): User
    {
        $configuredPassword = $this->configuredDemoPassword('staff_password');
        $user = User::firstOrCreate(
            ['email' => 'demo-seller@nuvia.local'],
            [
                'name' => 'Nuvia Demo Seller',
                'password' => Hash::make($configuredPassword ?: Str::random(32)),
                'shop_id' => $shop->id,
                'email_verified_at' => now(),
            ],
        );

        if ($configuredPassword !== null) {
            $user->forceFill(['password' => Hash::make($configuredPassword)])->save();
        }

        $this->grantStaffAccess($user, $shop);

        return $user;
    }

    protected function ensureDemoStaffAccount(Shop $shop): ?User
    {
        if ($this->configuredDemoPassword('staff_password') === null) {
            return null;
        }

        return $this->createDemoStaff($shop);
    }

    protected function resolveOrCreateDemoShop(): Shop
    {
        $configuredPassword = $this->configuredDemoPassword('owner_password');
        $owner = User::query()->firstOrNew(['email' => self::DEMO_OWNER_EMAIL]);
        $owner->forceFill([
            'name' => 'Nuvia Demo Owner',
            'password' => $configuredPassword
                ? Hash::make($configuredPassword)
                : ($owner->password ?: Hash::make(Str::random(32))),
            'is_active' => true,
            'email_verified_at' => $owner->email_verified_at ?: now(),
        ])->save();

        $shop = Shop::query()->firstOrNew(['slug' => self::DEMO_SHOP_SLUG]);
        $shop->forceFill([
            'owner_id' => $owner->id,
            'name' => 'Nuvia Demo Beauty',
            'slug' => self::DEMO_SHOP_SLUG,
            'is_active' => true,
            'settings' => $shop->settings ?: [
                'contact' => [
                    'emailAddress' => self::DEMO_CUSTOMER_EMAIL,
                ],
            ],
        ])->save();

        if ((int) $owner->shop_id !== (int) $shop->id) {
            $owner->forceFill(['shop_id' => $shop->id])->save();
        }

        return $shop;
    }

    protected function resolveOrCreateDemoAdmin(): ?User
    {
        $password = $this->configuredDemoPassword('admin_password');

        if ($password === null) {
            return null;
        }

        $email = trim((string) config('services.beauty_demo.admin_email', self::DEMO_ADMIN_EMAIL));
        if ($email === '') {
            $email = self::DEMO_ADMIN_EMAIL;
        }

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->forceFill([
            'name' => 'Nuvia Demo Admin',
            'password' => Hash::make($password),
            'is_active' => true,
            'email_verified_at' => $user->email_verified_at ?: now(),
        ])->save();

        $this->grantSuperAdminAccess($user);

        return $user;
    }

    protected function ensurePublishedDemoCatalog(Shop $shop): Collection
    {
        $type = $this->resolveOrCreateDemoType();

        foreach ($this->demoCatalogTemplates() as $index => $template) {
            $product = Product::withTrashed()
                ->where('shop_id', $shop->id)
                ->where('sku', $template['sku'])
                ->first() ?? new Product();

            $product->forceFill([
                'name' => $template['name'],
                'slug' => $template['slug'],
                'description' => $template['description'],
                'type_id' => $type->id,
                'price' => $template['price'],
                'sale_price' => null,
                'shop_id' => $shop->id,
                'sku' => $template['sku'],
                'quantity' => 25 + $index,
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
                'deleted_at' => null,
            ])->save();
        }

        return $this->publishedProductsForShop($shop)->take(10)->values();
    }

    protected function resolveOrCreateDemoType(): Type
    {
        $type = Type::query()->firstOrNew(['slug' => self::DEMO_TYPE_SLUG]);
        $type->forceFill([
            'name' => 'Beauty Demo',
            'slug' => self::DEMO_TYPE_SLUG,
            'icon' => null,
            'promotional_sliders' => null,
            'images' => null,
        ])->save();

        return $type;
    }

    protected function demoCatalogTemplates(): array
    {
        /** @var array<int, array{name:string,slug:string,sku:string,price:int,description:string,source_dataset:string,source_brand:string,source_reference:string}> $catalog */
        $catalog = require database_path('seeders/data/phase6-demo-catalog.php');

        return $catalog;
    }

    protected function catalogSources(): array
    {
        return collect($this->demoCatalogTemplates())
            ->map(fn (array $template) => [
                'dataset' => $template['source_dataset'] ?? 'unknown',
                'brand' => $template['source_brand'] ?? null,
                'reference' => $template['source_reference'] ?? null,
            ])
            ->unique(fn (array $source) => implode('|', [
                $source['dataset'],
                $source['brand'] ?? '',
                $source['reference'] ?? '',
            ]))
            ->values()
            ->all();
    }

    protected function ensurePermissionGraph(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => UserPermission::SUPER_ADMIN, 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => UserPermission::STORE_OWNER, 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => UserPermission::STAFF, 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => UserPermission::CUSTOMER, 'guard_name' => 'api']);

        Role::firstOrCreate(['name' => UserRole::SUPER_ADMIN, 'guard_name' => 'api'])
            ->syncPermissions([UserPermission::SUPER_ADMIN, UserPermission::STORE_OWNER, UserPermission::CUSTOMER]);
        Role::firstOrCreate(['name' => UserRole::STORE_OWNER, 'guard_name' => 'api'])
            ->syncPermissions([UserPermission::STORE_OWNER, UserPermission::CUSTOMER]);
        Role::firstOrCreate(['name' => UserRole::STAFF, 'guard_name' => 'api'])
            ->syncPermissions([UserPermission::STAFF, UserPermission::CUSTOMER]);
        Role::firstOrCreate(['name' => UserRole::CUSTOMER, 'guard_name' => 'api'])
            ->syncPermissions([UserPermission::CUSTOMER]);
    }

    protected function cleanupPreviousDemoArtifacts(int $shopId): void
    {
        $existingSession = BeautySession::query()
            ->where('public_id', self::DEMO_SESSION_PUBLIC_ID)
            ->first();

        if ($existingSession) {
            $existingSession->delete();
        }

        MediaAsset::withTrashed()
            ->where('session_id', self::DEMO_SESSION_PUBLIC_ID)
            ->orWhere('object_key', self::DEMO_MEDIA_OBJECT_KEY)
            ->forceDelete();

        BeautyProfile::query()
            ->where('shop_id', $shopId)
            ->where('customer_name', self::DEMO_CUSTOMER_NAME)
            ->delete();
    }

    protected function publishedProductsForShop(Shop $shop): Collection
    {
        return Product::query()
            ->where('shop_id', $shop->id)
            ->where('status', ProductStatus::PUBLISH)
            ->orderBy('id')
            ->get();
    }

    protected function requiredRoutesPresent(): bool
    {
        $requiredUris = [
            'api/v1/storage/upload-slots',
            'api/v1/storage/media/{mediaId}/confirm',
            'api/v1/beauty/sessions',
            'api/v1/beauty/sessions/{id}/analysis/start',
            'api/v1/beauty/analysis/{taskId}/status',
            'api/v1/admin/beauty/recommendations/recompute',
        ];

        $registeredUris = collect(Route::getRoutes()->getRoutes())
            ->map(fn ($route) => trim($route->uri(), '/'))
            ->all();

        foreach ($requiredUris as $uri) {
            if (!in_array($uri, $registeredUris, true)) {
                return false;
            }
        }

        return true;
    }

    protected function artisanCommandExists(string $name): bool
    {
        return array_key_exists($name, Artisan::all());
    }

    protected function beautyStorageCheck(): array
    {
        $storageContract = (string) config('filesystems.storage_contract', 'local');
        $inputsBucket = trim((string) config('filesystems.disks.s3_beauty_inputs.bucket', ''));
        $resultsBucket = trim((string) config('filesystems.disks.s3_beauty_results.bucket', ''));
        $hasPrivateBeautyBuckets = $inputsBucket !== '' && $resultsBucket !== '';

        if ($hasPrivateBeautyBuckets) {
            return $this->check(
                'storage_bucket_configured',
                true,
                'Beauty storage buckets are configured.',
                [
                    'storage_contract' => $storageContract,
                    'inputs_bucket' => $inputsBucket,
                    'results_bucket' => $resultsBucket,
                ],
            );
        }

        if (app()->environment('testing')) {
            return $this->check(
                'storage_bucket_configured',
                true,
                'Testing mode allows the demo audit to pass without private bucket env vars because no live object storage is exercised.',
                [
                    'storage_contract' => $storageContract,
                    'inputs_bucket' => $inputsBucket,
                    'results_bucket' => $resultsBucket,
                ],
            );
        }

        return $this->check(
            'storage_bucket_configured',
            false,
            'Beauty storage buckets must be configured for non-testing environments.',
            [
                'storage_contract' => $storageContract,
                'inputs_bucket' => $inputsBucket,
                'results_bucket' => $resultsBucket,
            ],
        );
    }

    protected function check(string $key, bool $passed, string $message, array $context = []): array
    {
        return [
            'key' => $key,
            'passed' => $passed,
            'message' => $message,
            'context' => $context,
        ];
    }

    protected function assertDemoExecutionAllowed(array $options = []): void
    {
        if (app()->environment('production') && !$this->allowsProductionDemoOperations($options)) {
            throw new RuntimeException('Phase 6 demo preparation is blocked in production. Set BEAUTY_DEMO_ALLOW_PRODUCTION=true or pass --allow-production to continue.');
        }
    }

    protected function allowsProductionDemoOperations(array $options = []): bool
    {
        return (bool) ($options['allow_production'] ?? false)
            || (bool) config('services.beauty_demo.allow_production', false);
    }

    protected function configuredDemoPassword(string $key): ?string
    {
        $value = trim((string) config("services.beauty_demo.{$key}", ''));

        return $value !== '' ? $value : null;
    }

    protected function writeReport(array $report, string $reportPath): void
    {
        File::ensureDirectoryExists(dirname($reportPath));
        File::put($reportPath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}');
    }
}
