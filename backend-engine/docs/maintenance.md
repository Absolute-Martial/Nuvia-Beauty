# Backend Maintenance

Maintenance documentation for `backend-engine` in the Nuvia Beauty `development` branch.

## Purpose

This file defines the backend maintenance rules for dependency updates, configuration changes, route changes, database changes, storage work, queue work, and documentation updates.

## Current backend baseline

| Area | Current value |
|---|---|
| Directory | `backend-engine/` |
| Composer package | `nuvia/beauty-api` |
| PHP requirement | `^8.3` |
| Laravel framework | `^13.0` |
| Docker base image | `php:8.3-cli-alpine` |
| Default port | `8000` |
| Database | MySQL |
| Cache/session/queue service | Redis in Docker Compose |
| Default local queue | `sync` in `.env.example` |
| Default Compose queue | `redis` through `docker-compose.yml` |

## Maintenance rule

Every backend change must update the matching documentation file in the same commit when behavior changes.

| Change type | Documentation to update |
|---|---|
| Composer dependency change | `docs/tech-stack.md` |
| Dockerfile change | `docs/deployment.md`, `docs/tech-stack.md` |
| Environment variable change | `docs/environment.md` |
| Route change | `docs/api-routes.md` |
| Database migration/schema change | `docs/database.md` |
| Storage config/flow change | `docs/storage.md` |
| Queue/job/worker change | `docs/queues-jobs.md` |
| Request/data flow change | `docs/data-flow-implementation.md` |
| Feature responsibility change | `docs/features-processes.md` |

## Composer maintenance

After changing Composer dependencies:

```bash
composer update vendor/package
composer dump-autoload
php artisan package:discover --ansi
```

If dependency changes affect production builds, rebuild the backend image.

Required files to inspect:

```text
composer.json
composer.lock
backend-engine/Dockerfile
```

Do not change Laravel major version without checking frontend/API compatibility and package compatibility.

## Laravel cache maintenance

After environment/config changes:

```bash
php artisan config:clear
php artisan cache:clear
```

After route/view/cache-related deployment changes:

```bash
php artisan route:clear
php artisan view:clear
```

For optimized production environments, rebuild caches only after env is final:

```bash
php artisan config:cache
php artisan route:cache
```

## Database maintenance

Migration rules:

```text
Use Laravel migrations for schema changes.
Keep migrations reversible where practical.
Use transactions for multi-record consistency when supported.
Do not store raw file bytes in MySQL.
Do not store backend secrets in database tables.
```

Run migrations in production-like deployments with:

```bash
php artisan migrate --force
```

Before destructive schema changes:

```text
Create backup.
Document migration impact.
Verify rollback or forward-fix path.
Confirm dependent frontend/API behavior.
```

## Storage maintenance

Current status:

```text
FILESYSTEM_DISK=local is the .env.example default.
config/filesystems.php has local, public, and one public-oriented s3 disk.
Dedicated S3-compatible private disks are planned but not implemented yet.
```

Storage maintenance rules:

```text
Do not expose storage credentials to frontend apps.
Do not place storage secrets in NEXT_PUBLIC_* variables.
Do not store raw file bytes in MySQL.
Do not use original filenames as trusted object identity.
Do not make private beauty/user media public.
Use backend authorization before private media access.
```

When S3-compatible storage is implemented, update:

```text
backend-engine/config/filesystems.php
backend-engine/.env.example
backend-engine/docs/storage.md
backend-engine/docs/environment.md
root docs/storage.md
```

## Queue maintenance

Current status:

```text
Redis service exists in Docker Compose.
QUEUE_CONNECTION defaults to sync in .env.example.
QUEUE_CONNECTION defaults to redis in docker-compose.yml.
Dedicated queue worker service is not currently defined.
```

Queue maintenance rules:

```text
Do not enable redis queue in deployment without a worker process.
Long provider/storage operations should run as jobs, not request lifecycle work.
Jobs should be idempotent and retry-safe.
Jobs must not log secrets or full signed URLs.
```

When adding worker service, update:

```text
docker-compose.yml
docker-compose.dev.yml if needed
backend-engine/docs/queues-jobs.md
backend-engine/docs/deployment.md
root docs/architecture.md
```

## API route maintenance

When adding routes, document:

```text
HTTP method
path
middleware
authorization rule
request shape
response shape
owner service/domain
current/planned/deprecated status
```

Preferred future route style:

```text
/api/v1/{domain}/{resource}
```

Avoid route growth with unclear ownership. Split domain route files when route count grows.

## Secret maintenance

Backend-only secrets:

```text
APP_KEY
DB_PASSWORD
DB_ROOT_PASSWORD
S3/AWS access keys
MinIO/AIStor root credentials
YOUCAM_API_KEY
YOUCAM_API_BEARER_KEY
provider secrets
```

Rules:

```text
Never commit secrets.
Never return secrets from API endpoints.
Never log secrets.
Never place secrets in frontend NEXT_PUBLIC_* variables.
Rotate secrets after accidental exposure.
```

## Logging maintenance

Safe to log:

```text
record IDs
status codes
safe error codes
timestamps
actor IDs where appropriate
provider task IDs where safe
object key hashes or structured references
```

Do not log:

```text
raw API keys
DB passwords
S3 secret keys
MinIO/AIStor root credentials
full private signed URLs
raw image bytes
sensitive raw provider payloads
```

## Testing and verification checklist

Before merging backend behavior changes:

```text
composer validate
composer dump-autoload
php artisan config:clear
php artisan route:list if routes changed
php artisan migrate --pretend if migrations changed
php artisan test if tests exist and are configured
build backend Docker image if Dockerfile/dependencies changed
```

If no automated tests exist for a changed area, document manual verification steps in the PR or commit notes.

## Current known gaps

Current backend gaps documented for future work:

```text
Dedicated S3-compatible disks are not implemented.
S3-compatible env variables are not in .env.example.
MinIO/AIStor service is not in Docker Compose.
Upload-slot endpoints are not implemented.
Media asset table is not implemented.
Dedicated queue worker service is not implemented.
Package-provided route inventory is not documented yet.
```

## Documentation maintenance checklist

When changing backend behavior, check:

```text
backend-engine/docs/index.md
backend-engine/docs/tech-stack.md
backend-engine/docs/features-processes.md
backend-engine/docs/api-routes.md
backend-engine/docs/data-flow-implementation.md
backend-engine/docs/database.md
backend-engine/docs/environment.md
backend-engine/docs/storage.md
backend-engine/docs/queues-jobs.md
backend-engine/docs/deployment.md
backend-engine/docs/maintenance.md
root docs/architecture.md
root docs/services.md
root docs/storage.md
root docs/changelog.md
```

## Changelog rule

For architecture, storage, deployment, API, or database changes, add an entry to:

```text
docs/changelog.md
```

Include:

```text
date
changed files
reason
impact
migration notes if any
```
