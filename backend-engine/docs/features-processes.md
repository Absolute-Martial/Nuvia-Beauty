# Backend Features and Processes

Current and planned backend responsibilities for `backend-engine` in the Nuvia Beauty `development` branch.

## Current backend role

`backend-engine` is the protected Laravel API service for the platform.

Current responsibilities:

```text
API runtime
Business logic boundary
Database access boundary
Cache/session/queue integration point
Storage abstraction boundary
External provider integration boundary
```

Correct service boundary:

```text
frontend services -> backend-engine -> protected infrastructure
```

Frontend services must not directly access MySQL, Redis, storage credentials, or provider API keys.

## Current runtime process

Current backend Docker runtime starts the Laravel application through Artisan:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

The container exposes:

```text
8000
```

## Current Docker Compose process

Root `docker-compose.yml` defines:

```text
backend -> db
backend -> redis
```

The backend service receives shared variables from the Compose `x-backend-env` block.

Current production-like Compose behavior:

```text
Use image: ${NUVIA_BACKEND_IMAGE:-nuvia-beauty-backend:local}
Restart unless stopped
Expose host port 8000
Join network nuvia-beauty-net
```

Current development Compose behavior in `docker-compose.dev.yml`:

```text
Build ./backend-engine/Dockerfile
Mount ./backend-engine into /var/www/html
Mount backend_vendor volume into /var/www/html/vendor
Run php artisan serve
Set APP_ENV=local
```

## Current database process

Current database service:

```text
MySQL 8.0
```

Backend database connection is configured through environment variables:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=${DB_DATABASE:-nuvia_beauty}
DB_USERNAME=${DB_USERNAME:-nuvia_beauty}
DB_PASSWORD=${DB_PASSWORD:-nuvia_beauty}
```

The backend is the only application service that should talk to the database.

## Current Redis process

Current Redis service:

```text
Redis 7.4 Alpine
```

Compose config sets backend Redis variables:

```env
REDIS_HOST=redis
REDIS_PORT=6379
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

The default `.env.example` still uses local development defaults:

```env
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
```

This means queue behavior depends on environment.

## Current API process

The app route file currently defines the authenticated `/user` route in:

```text
backend-engine/routes/api.php
```

Current route:

```php
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
```

Additional routes may be registered by packages or service providers, especially through the local `marvel/shop` package. This document only records routes visible directly in the app-level `routes/api.php` file.

## Current storage process

Current Laravel filesystem disks:

```text
local
public
s3
```

Current default disk in `.env.example`:

```env
FILESYSTEM_DISK=local
```

Current status:

- Local filesystem is the default.
- A public-oriented `s3` disk exists in config.
- S3-compatible dependency exists through `league/flysystem-aws-s3-v3`.
- Dedicated private S3-compatible disks are planned but not implemented yet.

## Planned storage process

Planned protected upload flow:

```text
1. Frontend requests upload slot from backend.
2. Backend validates actor, file intent, MIME type, size, and ownership.
3. Backend creates pending media metadata.
4. Backend returns short-lived presigned upload URL.
5. Browser uploads directly to S3-compatible storage.
6. Browser confirms upload with backend.
7. Backend verifies object metadata and marks media confirmed.
```

Planned private read flow:

```text
1. Frontend requests private media URL from backend.
2. Backend checks auth and ownership.
3. Backend returns short-lived signed download URL.
4. Backend logs access without logging the signed URL.
```

## Current provider process

Current `.env.example` includes YouCam/Perfect Corp variables:

```env
YOUCAM_API_BASE_URL=https://yce-api-01.makeupar.com
YOUCAM_API_KEY=
YOUCAM_API_BEARER_KEY=
```

Current rule:

```text
Provider secrets are backend-only.
```

Frontend services must not receive provider keys through `NEXT_PUBLIC_*` variables or API responses.

## Planned provider process

Protected provider orchestration should live in backend services/jobs.

Correct flow:

```text
frontend -> backend request -> quota/storage validation -> backend job -> provider API -> normalized result -> database -> frontend result endpoint
```

Provider integration should not be called directly from browser code.

## Current authentication boundary

Current app-level route uses:

```php
auth:api
```

Authentication implementation details may be defined by Laravel configuration or the commerce package. Any new protected API routes should use existing auth middleware consistently and must not trust frontend-provided user IDs.

## Backend process rules

Controllers should stay thin.

Preferred pattern:

```text
Controller -> Request validation -> Service -> Repository/model/storage/provider layer -> Response DTO/resource
```

Avoid:

```text
Controller directly performs storage/provider/database orchestration
```

## Feature classification labels

Use these labels in backend docs and comments:

```text
Current
Planned
Not implemented yet
Deprecated
Do not use
```

## Current known gaps

Current backend gaps:

```text
Dedicated S3-compatible public/private disks are not implemented yet.
Upload-slot endpoints are not implemented yet.
Media asset table is not implemented yet.
Queue worker service is not defined in Compose yet.
Dedicated provider orchestration jobs are not documented in current app-level routes.
No backend-specific docs existed before this docs set.
```

## Implementation discipline

For new backend features:

1. Add or update service-specific docs first or in the same commit.
2. Keep secrets backend-only.
3. Add route documentation when routes are added.
4. Add database documentation when migrations are added.
5. Add storage documentation when disks, buckets, or media flows change.
6. Add queue documentation when jobs, retries, or workers are added.
