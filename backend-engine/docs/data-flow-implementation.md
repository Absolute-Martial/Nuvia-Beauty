# Backend Data Flow and Implementation

Current backend implementation flow for Phase 4.

## Service boundary

Protected logic remains:

```text
frontend apps -> backend-engine -> MySQL / Redis / S3-compatible storage
```

Protected logic does not move into:

- Next.js API routes
- frontend browser code
- direct database access from the UI

## Current Phase 4 flows

### Storage upload flow

```text
Frontend request
  -> POST /api/v1/storage/upload-slots
  -> StorageController
  -> MediaAssetService
  -> beauty_media_assets pending row
  -> S3CompatibleStorageService presigned PUT URL
  -> browser uploads directly to object storage
  -> POST /api/v1/storage/media/{mediaId}/confirm
  -> object existence check
  -> metadata status becomes confirmed
```

### Private media read flow

```text
Frontend request
  -> GET /api/v1/storage/media/{mediaId}/download-url
  -> MediaAssetPolicy authorization
  -> S3CompatibleStorageService presigned GET URL
  -> browser reads the object with short-lived access
```

### Product mapping flow

```text
Admin or authorized shop actor
  -> POST/PUT /api/v1/beauty/product-mappings
  -> BeautyProductMappingController
  -> product ownership/role validation
  -> beauty_product_mappings upsert
```

### Recommendation flow

```text
Storefront request
  -> POST /api/v1/beauty/recommendations/generate
  -> BeautyRecommendationController
  -> normalized profile tags
  -> BeautyProductMapping collection
  -> RecommendationScoringService deterministic weighted scoring
  -> RecommendationExplanationService reasons and warnings
  -> beauty_recommendations persistence
  -> JSON response to storefront
```

## Current recommendation scoring model

Inputs:

- `skin_type_tags`
- `tone_tags`
- `undertone_tags`
- `concern_tags`
- `ingredient_tags`
- `avoid_tags`
- mapped product tags

Current weighted factors:

```text
skin_type_match   0.22
tone_match        0.14
undertone_match   0.14
concern_match     0.24
ingredient_match  0.12
product_tag_signal 0.14
avoid_penalty     -55 points when avoid overlap exists
```

Outputs:

- score `0-100`
- confidence `low|medium|high`
- reasons
- warnings
- breakdown object

The wording intentionally avoids medical or clinical diagnosis language.

## Current storefront integration flow

```text
Customer opens product detail page
  -> storefront recommendation panel gathers profile tags
  -> frontend calls backend REST endpoint only
  -> backend returns scored recommendation list
  -> storefront renders score badge, reasons, warnings, and product link
```

Frontend security rule:

- browser requests never receive storage credentials or provider secrets

## Current admin and vendor support model

This phase ships a controlled backend workflow instead of a full dashboard mapping UI.

Supported now:

- backend product mapping API
- controlled seed helper for the first 10 existing products

Controlled seed command:

```bash
php artisan db:seed --class=Database\\Seeders\\BeautyProductMappingSeeder
```

This avoids overbuilding marketplace dashboards before ownership and moderation workflows are fully specified.

## Current cleanup path

Implemented:

- `DeleteExpiredMediaAssets` job

Not yet wired:

- recurring scheduler registration for expired-object cleanup
- beauty events and product signal foundation
