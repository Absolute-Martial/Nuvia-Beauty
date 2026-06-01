# Phase 2: Mapping Editors and Event Signals Foundation

## Status

```text
Reported locally implemented in commit cd2983c.
Remote GitHub confirmation pending because push failed with 403.
```

## Objective

Add the second Phase 4 layer: admin/vendor beauty mapping editors and Epic 7 event/product-signal foundation.

## Scope

In scope:

```text
beauty event ingestion API
beauty product signal aggregation
recompute job
recompute artisan command
daily scheduler entry
admin inline mapping editor
vendor inline mapping editor
docs updates
```

Out of scope:

```text
full admin mapping console
full analytics dashboard
Perfect Corp integration
seller consultation sessions
moderation/approval workflow for vendor mappings
```

## Reported implementation files

Backend reported files:

```text
backend-engine/routes/api/v1/beauty.php
backend-engine/app/Domains/Beauty/Controllers/BeautyEventController.php
backend-engine/app/Domains/Beauty/Controllers/BeautyRecommendationController.php
backend-engine/app/Domains/Beauty/Models/BeautyEvent.php
backend-engine/app/Domains/Beauty/Models/BeautyProductSignal.php
backend-engine/app/Domains/Beauty/Services/BeautyEventService.php
backend-engine/app/Domains/Beauty/Services/BeautyProductSignalService.php
backend-engine/app/Domains/Beauty/Jobs/RecomputeBeautyProductSignals.php
backend-engine/app/Console/Commands/RecomputeBeautyProductSignalsCommand.php
backend-engine/app/Console/Kernel.php
backend-engine/database/migrations/2026_05_28_000004_create_beauty_events_table.php
backend-engine/database/migrations/2026_05_28_000005_create_beauty_product_signals_table.php
```

Admin reported files:

```text
admin-panel/src/data/client/api-endpoints.ts
admin-panel/src/data/client/beauty-product-mapping.ts
admin-panel/src/data/beauty-product-mapping.ts
admin-panel/src/components/product/beauty-mapping-editor.tsx
admin-panel/src/components/product/product-form.tsx
```

Vendor reported files:

```text
vendor-portal/src/data/client/api-endpoints.ts
vendor-portal/src/data/client/beauty-product-mapping.ts
vendor-portal/src/data/beauty-product-mapping.ts
vendor-portal/src/components/product/beauty-mapping-editor.tsx
vendor-portal/src/components/product/product-form.tsx
```

## APIs expected

```http
POST /api/v1/beauty/events
POST /api/v1/admin/beauty/recommendations/recompute
```

## Expected command

```bash
php artisan beauty:recompute-product-signals
```

## Required validation

Run:

```bash
cd backend-engine
php artisan migrate
php artisan list | grep beauty
php artisan route:list --path=api/v1
```

Frontend:

```bash
yarn build:admin-panel
yarn build:vendor-portal
```

Manual checks:

```text
admin product edit loads beauty mapping editor
admin can save beauty mapping tags
admin can trigger recompute for product if supported
vendor product edit loads beauty mapping editor
vendor can save mappings for owned products
vendor cannot update another vendor product
POST /api/v1/beauty/events accepts view/add_to_cart/purchase
recompute command updates product signals
```

## Known issue

Reported result says `php artisan route:list` is blocked by DB-backed settings during console startup.

This must be fixed in Phase 3 before production deployment.

## Acceptance criteria

```text
Remote commit exists on development branch.
Migrations run.
Routes registered.
Admin editor builds and saves.
Vendor editor builds and saves for owned products.
Event ingestion works.
Recompute command exists.
Docs are updated.
```
