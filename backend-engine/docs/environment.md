# Backend Environment

Current backend environment documentation through Phase 5.

## Core runtime variables

| Variable | Purpose | Secret |
|---|---|---|
| `APP_NAME` | Laravel app name | no |
| `APP_ENV` | runtime environment | no |
| `APP_KEY` | Laravel encryption key | yes |
| `APP_DEBUG` | debug mode | no |
| `APP_URL` | backend base URL | no |

## Database variables

| Variable | Purpose | Secret |
|---|---|---|
| `DB_CONNECTION` | database driver | no |
| `DB_HOST` | database host | no |
| `DB_PORT` | database port | no |
| `DB_DATABASE` | database name | no |
| `DB_USERNAME` | database user | yes |
| `DB_PASSWORD` | database password | yes |

## Cache, session, and queue variables

| Variable | Purpose | Secret |
|---|---|---|
| `CACHE_DRIVER` | cache backend | no |
| `QUEUE_CONNECTION` | queue backend | no |
| `SESSION_DRIVER` | session backend | no |
| `SESSION_LIFETIME` | session lifetime | no |
| `REDIS_HOST` | redis host | no |
| `REDIS_PORT` | redis port | no |

## Frontend URL variables

| Variable | Purpose | Secret |
|---|---|---|
| `FRONTEND_URL` | customer storefront URL | no |
| `ADMIN_URL` | admin panel URL | no |
| `VENDOR_URL` | vendor portal URL | no |
| `SANCTUM_STATEFUL_DOMAINS` | Sanctum browser domain list | no |

## Phase 4 storage variables

Provider-neutral S3-compatible variables:

| Variable | Purpose | Secret |
|---|---|---|
| `STORAGE_DRIVER` | storage contract selector | no |
| `S3_PROVIDER` | provider label | no |
| `S3_ENDPOINT` | S3-compatible endpoint | no |
| `S3_REGION` | S3 region label | no |
| `S3_ACCESS_KEY_ID` | access key | yes |
| `S3_SECRET_ACCESS_KEY` | secret key | yes |
| `S3_USE_PATH_STYLE_ENDPOINT` | path-style toggle | no |
| `S3_PUBLIC_BUCKET` | public bucket name | no |
| `S3_BEAUTY_INPUTS_BUCKET` | private input bucket | no |
| `S3_BEAUTY_RESULTS_BUCKET` | private result bucket | no |
| `S3_BEAUTY_CALIBRATION_BUCKET` | private calibration bucket | no |
| `S3_UPLOAD_URL_TTL_MINUTES` | presigned upload TTL | no |
| `S3_DOWNLOAD_URL_TTL_MINUTES` | presigned download TTL | no |

Compatibility variables still supported:

| Variable | Purpose | Secret |
|---|---|---|
| `AISTOR_ACCESS_KEY_ID` | legacy AIStor access key fallback | yes |
| `AISTOR_SECRET_ACCESS_KEY` | legacy AIStor secret fallback | yes |
| `AISTOR_REGION` | legacy AIStor region fallback | no |
| `AISTOR_BUCKET` | legacy public bucket fallback | no |
| `AISTOR_PUBLIC_URL` | public object URL base | no |
| `AISTOR_ENDPOINT` | legacy object endpoint fallback | no |
| `AISTOR_USE_PATH_STYLE_ENDPOINT` | legacy path-style toggle | no |
| `AISTOR_BUCKET_ENDPOINT` | legacy bucket routing toggle | no |
| `AISTOR_ROOT_PREFIX` | optional object prefix | no |

## Current example values

`backend-engine/.env.example` now includes:

```env
FILESYSTEM_DISK=local
FILESYSTEM_CLOUD=s3
IMPORT_FILESYSTEM_DISK=local
MEDIA_DISK=public
STORAGE_DRIVER=s3_compatible
S3_PROVIDER=minio_aistor
S3_REGION=us-east-1
S3_USE_PATH_STYLE_ENDPOINT=true
S3_PUBLIC_BUCKET=nuvia-public-assets
S3_BEAUTY_INPUTS_BUCKET=nuvia-private-beauty-inputs
S3_BEAUTY_RESULTS_BUCKET=nuvia-private-beauty-results
S3_BEAUTY_CALIBRATION_BUCKET=nuvia-private-calibration
S3_UPLOAD_URL_TTL_MINUTES=15
S3_DOWNLOAD_URL_TTL_MINUTES=60
```

## Security rules

These values must remain backend-only:

- `APP_KEY`
- `DB_PASSWORD`
- `S3_ACCESS_KEY_ID`
- `S3_SECRET_ACCESS_KEY`
- `AISTOR_ACCESS_KEY_ID`
- `AISTOR_SECRET_ACCESS_KEY`
- provider API keys

Do not place backend secrets in frontend `NEXT_PUBLIC_*` variables.

## Phase 5 Perfect Corp variables

| Variable | Purpose | Secret |
|---|---|---|
| `PERFECT_CORP_API_BASE_URL` | provider base URL | no |
| `PERFECT_CORP_API_KEY` | provider API key | yes |
| `PERFECT_CORP_API_BEARER_KEY` | provider bearer key | yes |
| `PERFECT_CORP_ENABLED` | live provider toggle | no |
| `PERFECT_CORP_DEMO_MODE` | demo-first mode toggle | no |
| `PERFECT_CORP_TIMEOUT_SECONDS` | provider HTTP timeout | no |
| `PERFECT_CORP_POLL_INTERVAL_SECONDS` | live poll delay | no |
| `PERFECT_CORP_MAX_ATTEMPTS` | maximum live poll attempts | no |
| `BEAUTY_DEMO_ALLOW_PRODUCTION` | explicit override for demo prep/audit in `APP_ENV=production` | no |
| `BEAUTY_DEMO_AUTO_PREPARE` | auto-run Phase 6 demo prep during `backend-init` | no |

Safe Phase 5 default:

```text
PERFECT_CORP_ENABLED=false
PERFECT_CORP_DEMO_MODE=true
```

Phase 5 uses backend-only Perfect Corp integration. The vendor frontend only receives normalized analysis summaries and recommendation output.
`PerfectCorpClient::selfCheck()` now stays no-op while disabled or in demo mode, and only performs a live connectivity probe when both `PERFECT_CORP_ENABLED=true` and `PERFECT_CORP_DEMO_MODE=false`.

## Phase 6 demo deployment variables

| Variable | Purpose | Secret |
|---|---|---|
| `BEAUTY_DEMO_ALLOW_PRODUCTION` | permits `beauty:prepare-demo` and `beauty:audit-demo-readiness` while `APP_ENV=production` | no |
| `BEAUTY_DEMO_AUTO_PREPARE` | runs demo prep and audit from `backend-init` after migrate + settings seed | no |

Safe defaults:

```text
BEAUTY_DEMO_ALLOW_PRODUCTION=false
BEAUTY_DEMO_AUTO_PREPARE=false
```

Use both only for a dedicated demo/staging deployment where you intentionally want the Phase 6 catalog and saved consultation to be bootstrapped on deploy.

## Current runtime note

For production bootstrap in `docker-compose.production.yml`, backend startup explicitly forces:

```text
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

This avoids the existing runtime dependency on PHP Redis extensions during migrations and early boot.

## Queue worker note

Phase 5 dispatches `CreatePerfectCorpAnalysisTask` and `PollPerfectCorpAnalysisTask`.

- `QUEUE_CONNECTION=sync` is acceptable for local validation and demo mode.
- async environments should run a queue worker before live Perfect Corp mode is enabled.

## Demo audit testing note

`php artisan beauty:audit-demo-readiness` still requires private beauty bucket configuration in non-testing environments.

For `APP_ENV=testing`, the command now allows a metadata-only fallback when `S3_BEAUTY_INPUTS_BUCKET` and `S3_BEAUTY_RESULTS_BUCKET` are unset. This keeps CI and local test runs green when no live S3-compatible object storage is exercised.

This fallback is limited to the audit command path in testing. It does not relax runtime storage requirements for actual upload, confirm, signed download, or live analysis flows.

## Demo catalog note

The deterministic Phase 6 catalog is now curated from public beauty dataset samples and bundled in `backend-engine/database/seeders/data/phase6-demo-catalog.php`.

- Sephora sample dataset rows provide named prestige-beauty products and tools.
- Open Beauty Facts public API samples provide moisturizer and serum inventory variety.

The generated `beauty:prepare-demo` report now includes a `catalog_sources` section so presenters can disclose where the demo assortment came from.

## Console-safe settings fallback

`backend-engine` still uses DB-backed commerce settings at runtime. That behavior has not been removed.

For Laravel console commands only, the app now falls back to the same default settings payload used by `SettingsSeeder` when either of these is true:

- the `settings` table is not available yet
- the console command is booting before settings have been seeded

This keeps commands such as:

```bash
php artisan migrate
php artisan route:list --path=api/v1
php artisan list | grep beauty
```

from failing during bootstrap because of an early `Settings::first()` lookup.

The fallback is in-memory only. It does not write settings rows, and normal HTTP/runtime behavior continues to prefer the database-backed `settings` record.
