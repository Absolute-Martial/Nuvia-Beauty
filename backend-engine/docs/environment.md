# Backend Environment

Current backend environment documentation for Phase 4.

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

## Current runtime note

For production bootstrap in `docker-compose.production.yml`, backend startup explicitly forces:

```text
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

This avoids the existing runtime dependency on PHP Redis extensions during migrations and early boot.
