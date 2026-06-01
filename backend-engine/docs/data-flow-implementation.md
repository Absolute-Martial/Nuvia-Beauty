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

### Seller consultation session flow

```text
Vendor starts consultation in vendor-portal
  -> POST /api/v1/beauty/sessions
  -> BeautySessionService creates beauty_profiles row
  -> BeautyProfileSnapshot freezes current consultation criteria
  -> BeautySession enters draft state
  -> BeautyQuotaAccount/Event foundation records session_created
```

### Consultation media attachment flow

```text
Vendor requests private upload slot
  -> POST /api/v1/storage/upload-slots with purpose=beauty_input
  -> browser uploads directly to S3-compatible storage
  -> POST /api/v1/storage/media/{mediaId}/confirm
  -> POST /api/v1/beauty/sessions/{id}/attach-media
  -> BeautySession links primary media asset
  -> BeautySession enters media_uploaded state
```

### Perfect Corp P0 analysis flow

```text
Vendor starts analysis for an attached consultation image
  -> POST /api/v1/beauty/sessions/{id}/analysis/start
  -> BeautySessionService validates seller access, media ownership, privacy, and quota state
  -> BeautyAiTask row is created in queued state
  -> BeautyAnalysisResult row is created in pending state
  -> CreatePerfectCorpAnalysisTask is dispatched
  -> in demo mode, no provider call is made
  -> NormalizePerfectCorpResultService generates deterministic sample output
  -> in live mode, PerfectCorpClient submits and polls provider task status
  -> BeautyProfileSnapshot is created from normalized traits
  -> BeautyRecommendation rows are regenerated from the updated snapshot
  -> BeautySession enters analysis_completed state
  -> GET /api/v1/beauty/analysis/{taskId}/status returns normalized summary only
```

Testing note:

- `beauty:audit-demo-readiness` can validate the Phase 5 consultation and recommendation flow in `APP_ENV=testing` without live private bucket env vars.
- The testing fallback is audit-only and does not bypass real storage requirements for uploads or signed downloads.

### Seller consultation recommendation flow

```text
Vendor requests recommendations for a session
  -> GET /api/v1/beauty/sessions/{id}/recommendations
  -> BeautySessionService loads the frozen snapshot criteria
  -> BeautyProductMapping query is restricted to the managed shop
  -> deterministic RecommendationScoringService ranks mapped products
  -> BeautyRecommendation rows are persisted with session_id = beauty_sessions.public_id
  -> recommendations can be refreshed after demo/live Perfect Corp normalization
```

### Save and discard flow

```text
Vendor saves or discards consultation
  -> POST /api/v1/beauty/sessions/{id}/save or /discard
  -> BeautySession state changes to saved or discarded
  -> audit_logs receives a lightweight consultation action row
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

Supported now:

- backend product mapping API
- admin beauty mapping overview and recompute controls
- vendor inline beauty mapping editor for owned products
- vendor consultation page for seller-assisted beauty sessions
- controlled beauty mapping seed helper for empty or early catalogs

## Kaggle dataset adaptation flow

```text
Operator downloads cosmetic-brand-products-dataset locally
  -> php artisan beauty:seed-kaggle-catalog /path/to/cosmetic-brand-products-dataset.zip
  -> KaggleBeautyCatalogImportService reads CSV or ZIP locally
  -> source rows normalize into beauty catalog families
  -> first 40 existing commerce products are selected by id order
  -> beauty_product_mappings are upserted for the existing products
  -> JSON import report is written for demo validation
```

This flow keeps the commerce `products` table stable while making the beauty metadata look and feel like it was adapted from a real cosmetics catalog.

## Current cleanup path

Implemented:

- `DeleteExpiredMediaAssets` job

Implemented now:

- beauty event capture under `/api/v1/beauty/events`
- deterministic product signal aggregation under `beauty_product_signals`
- admin-only product signal recompute endpoint and scheduled command

Not yet wired:

- recurring scheduler registration for expired-object cleanup
