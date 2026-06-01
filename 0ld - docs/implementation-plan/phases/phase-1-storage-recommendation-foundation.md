# Phase 1: Storage and Recommendation Foundation

## Status

```text
Reported locally implemented in commit de98e00.
Remote GitHub confirmation pending because push failed with 403.
```

## Objective

Implement the first Phase 4 foundation: backend-owned S3-compatible storage, media metadata, beauty product mappings, deterministic recommendation APIs, and storefront recommendation UI.

## Scope

In scope:

```text
S3-compatible Laravel filesystem configuration
public/private storage disk separation
media metadata model and migration
upload-slot API
confirm upload API
private signed download API
delete/discard job foundation
beauty product mapping model/API
deterministic recommendation generation API
storefront recommendation UI
documentation updates
```

Out of scope:

```text
Perfect Corp live provider integration
seller consultation sessions
beauty profile snapshots
admin/vendor full mapping console
Epic 7 event/product-signal loop
```

## Reported implementation files

Reported changed areas:

```text
backend-engine/config/filesystems.php
backend-engine/.env.example
backend-engine/routes/api.php
backend-engine/routes/api/v1/storage.php
backend-engine/routes/api/v1/beauty.php
backend-engine/app/Domains/Storage/*
backend-engine/app/Domains/Beauty/*
backend-engine/database/migrations/2026_05_28_000001_create_beauty_media_assets_table.php
backend-engine/database/migrations/2026_05_28_000002_create_beauty_product_mappings_table.php
backend-engine/database/migrations/2026_05_28_000003_create_beauty_recommendations_table.php
backend-engine/database/seeders/BeautyProductMappingSeeder.php
storefront/src/framework/rest/beauty-recommendations.ts
storefront/src/components/product/beauty-recommendation-panel.tsx
storefront/src/components/product/recommendation-card.tsx
storefront/src/components/product/recommendation-reason-list.tsx
storefront/src/components/product/recommendation-warning-list.tsx
storefront/src/components/product/recommendation-score-badge.tsx
storefront/src/components/product/product-single-details.tsx
```

## APIs expected

```http
POST /api/v1/storage/upload-slots
POST /api/v1/storage/media/{mediaId}/confirm
GET  /api/v1/storage/media/{mediaId}/download-url
DELETE /api/v1/storage/media/{mediaId}

GET  /api/v1/beauty/product-mappings
POST /api/v1/beauty/product-mappings
PUT  /api/v1/beauty/product-mappings/{id}

POST /api/v1/beauty/recommendations/generate
GET  /api/v1/beauty/recommendations/{id}
```

## Required validation

Run:

```bash
cd backend-engine
php artisan migrate
php artisan route:list --path=api/v1
```

Run frontend builds:

```bash
yarn build:storefront
yarn build:admin-panel
yarn build:vendor-portal
```

Storage validation:

```text
invalid MIME rejected
oversized file rejected
valid upload-slot returns signed PUT URL
PUT to signed URL works
confirm endpoint verifies object
private direct object URL fails
signed download URL works
```

Recommendation validation:

```text
recommendation endpoint returns score
returns confidence
returns reasons
returns warnings
returns breakdown
storefront renders loading/error/empty/success states
```

## Acceptance criteria

```text
Remote commit exists on development branch.
Storage routes are registered.
Migrations run.
Storefront builds.
Recommendation UI renders.
No frontend storage credentials are exposed.
Docs are updated.
```

## Follow-up after confirmation

Proceed to Phase 2 only after Phase 1 is pushed and route registration is verified.
