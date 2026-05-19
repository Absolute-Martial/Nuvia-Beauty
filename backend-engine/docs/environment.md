# Backend Environment

Environment documentation for `backend-engine` in the Nuvia Beauty `development` branch.

## Scope

This file documents backend environment variables from:

```text
backend-engine/.env.example
root docker-compose.yml
root docker-compose.dev.yml
```

Use this file to understand current runtime configuration and secret boundaries.

## Current `.env.example` baseline

Current application variables:

```env
APP_NAME=Stylefit
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
```

Current logging variables:

```env
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

Current database variables:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stylefit
DB_USERNAME=stylefit
DB_PASSWORD=
DB_SOCKET=
```

Current cache/queue/session variables:

```env
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Current frontend URL variables:

```env
SANCTUM_STATEFUL_DOMAINS=localhost:3003,localhost:3002
FRONTEND_URL=http://localhost:3003
ADMIN_URL=http://localhost:3002
```

Current filesystem variable:

```env
FILESYSTEM_DISK=local
```

Current YouCam/Perfect Corp variables:

```env
YOUCAM_API_BASE_URL=https://yce-api-01.makeupar.com
YOUCAM_API_KEY=
YOUCAM_API_BEARER_KEY=
```

Current Stylefit/Zyro path variables:

```env
STYLEFIT_SETTINGS_PATH=storage/app/private/zyro/settings/zyro.settings.json
STYLEFIT_TRYON_SOURCE_PATH=storage/app/private/zyro/try-on/source-photos
STYLEFIT_TRYON_RESULT_PATH=storage/app/private/zyro/try-on/results
```

Current feature flag variables:

```env
FLAGS_ENABLED=false
FLAGS_PROVIDER=local
```

Current Sentry variable:

```env
SENTRY_LARAVEL_DSN=
```

## Docker Compose backend environment

Root `docker-compose.yml` uses an `x-backend-env` block for the backend service.

Current Compose application variables:

```env
APP_NAME=Nuvia Beauty
APP_ENV=production
APP_DEBUG=false
APP_URL=${APP_URL:-http://localhost:8000}
APP_KEY=${APP_KEY:?APP_KEY is required}
```

Current Compose database variables:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=${DB_DATABASE:-nuvia_beauty}
DB_USERNAME=${DB_USERNAME:-nuvia_beauty}
DB_PASSWORD=${DB_PASSWORD:-nuvia_beauty}
```

Current Compose Redis/session/queue variables:

```env
CACHE_DRIVER=${CACHE_DRIVER:-redis}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-redis}
SESSION_DRIVER=${SESSION_DRIVER:-redis}
REDIS_HOST=redis
REDIS_PORT=6379
```

Current Compose frontend URL variables:

```env
FRONTEND_URL=${FRONTEND_URL:-http://localhost:3003}
ADMIN_URL=${ADMIN_URL:-http://localhost:3002}
VENDOR_URL=${VENDOR_URL:-http://localhost:3004}
```

## Development Compose override

`docker-compose.dev.yml` overrides backend runtime behavior:

```yaml
backend:
  build:
    context: ./backend-engine
    dockerfile: Dockerfile
    network: host
  command: php artisan serve --host 0.0.0.0 --port 8000
  environment:
    APP_ENV: local
  volumes:
    - ./backend-engine:/var/www/html
    - backend_vendor:/var/www/html/vendor
```

## Secret handling rules

Backend-only secrets must never be exposed to frontend code or `NEXT_PUBLIC_*` variables.

Backend-only secrets include:

```text
APP_KEY
DB_PASSWORD
DB_ROOT_PASSWORD
REDIS_PASSWORD if added later
S3_ACCESS_KEY_ID
S3_SECRET_ACCESS_KEY
AWS_ACCESS_KEY_ID
AWS_SECRET_ACCESS_KEY
MINIO_ROOT_USER
MINIO_ROOT_PASSWORD
YOUCAM_API_KEY
YOUCAM_API_BEARER_KEY
SENTRY_LARAVEL_DSN if sensitive in deployment context
```

## Browser-visible warning

Any variable beginning with `NEXT_PUBLIC_` belongs to frontend services and is visible in the browser bundle.

Do not place backend secrets in:

```text
NEXT_PUBLIC_REST_API_ENDPOINT
NEXT_PUBLIC_SITE_URL
NEXT_PUBLIC_SHOP_URL
NEXT_PUBLIC_VENDOR_URL
NEXT_PUBLIC_AUTH_TOKEN_KEY
```

## Current environment mismatch notes

Current `.env.example` and Docker Compose differ in several places:

| Setting | `.env.example` | Docker Compose |
|---|---|---|
| `APP_NAME` | `Stylefit` | `Nuvia Beauty` |
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `DB_HOST` | `127.0.0.1` | `db` |
| `DB_DATABASE` | `stylefit` | `nuvia_beauty` default |
| `CACHE_DRIVER` | `file` | `redis` default |
| `QUEUE_CONNECTION` | `sync` | `redis` default |
| `SESSION_DRIVER` | `file` | `redis` default |
| `FILESYSTEM_DISK` | `local` | not currently set in Compose backend env |

This is acceptable if intentional, but must be considered during debugging.

## Planned S3-compatible storage variables

Not implemented yet in `.env.example`.

Recommended future variables:

```env
STORAGE_DRIVER=s3_compatible
S3_PROVIDER=minio_aistor
S3_ENDPOINT=http://minio:9000
S3_REGION=us-east-1
S3_ACCESS_KEY_ID=
S3_SECRET_ACCESS_KEY=
S3_USE_PATH_STYLE_ENDPOINT=true

S3_PUBLIC_BUCKET=nuvia-public-assets
S3_BEAUTY_INPUTS_BUCKET=nuvia-private-beauty-inputs
S3_BEAUTY_RESULTS_BUCKET=nuvia-private-beauty-results
S3_BEAUTY_CALIBRATION_BUCKET=nuvia-private-calibration

S3_UPLOAD_URL_TTL_MINUTES=15
S3_DOWNLOAD_URL_TTL_MINUTES=60
S3_SERVER_SIDE_ENCRYPTION=AES256
```

## Provider environment rule

Provider API keys must stay in backend env only.

Correct:

```text
backend-engine env -> backend service/job -> provider API
```

Incorrect:

```text
frontend NEXT_PUBLIC_* -> provider API
```

## Config cache rule

After changing backend environment variables in a deployed Laravel environment, clear/rebuild config cache as needed:

```bash
php artisan config:clear
php artisan cache:clear
```

If using optimized production config later:

```bash
php artisan config:cache
```

## Update rule

Update this file whenever:

```text
.env.example changes
Docker Compose backend env changes
new backend secret variables are introduced
storage/provider variables are added
queue/cache/session drivers change
frontend URL variables change
```
