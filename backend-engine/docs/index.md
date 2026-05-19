# Backend Engine Documentation

Documentation for the `backend-engine` service in the Nuvia Beauty `development` branch.

## Service identity

| Field | Current value |
|---|---|
| Directory | `backend-engine/` |
| Composer package | `nuvia/beauty-api` |
| Runtime | Laravel API backend |
| PHP requirement | `^8.3` |
| Laravel version constraint | `^13.0` |
| Default port | `8000` |
| Docker base image | `php:8.3-cli-alpine` |
| Database | MySQL |
| Cache/session/queue service | Redis in Docker Compose, file/sync defaults in `.env.example` |

## Documentation files

| File | Purpose |
|---|---|
| `docs/index.md` | Backend documentation entry point. |
| `docs/tech-stack.md` | PHP, Laravel, Composer dependencies, Docker runtime, and installed extensions. |
| `docs/features-processes.md` | Current backend responsibilities, service processes, and implementation boundaries. |
| `docs/api-routes.md` | Current API routes and future API route conventions. |
| `docs/data-flow-implementation.md` | Request, database, queue, storage, and provider data flows. |
| `docs/database.md` | MySQL usage, migration rules, and data ownership. |
| `docs/environment.md` | Current `.env.example`, Docker Compose env, and secret handling. |
| `docs/storage.md` | Current filesystem disks and planned S3-compatible/MinIO AIStor direction. |
| `docs/queues-jobs.md` | Queue connection state, Redis queue direction, and job rules. |
| `docs/deployment.md` | Dockerfile behavior, Compose usage, runtime permissions, and deployment notes. |
| `docs/maintenance.md` | Maintenance rules, known gaps, and update checklist. |

## Backend responsibility

`backend-engine` owns protected business logic and persistent data access.

Correct boundary:

```text
Frontend apps -> backend-engine -> MySQL / Redis / storage / external providers
```

Frontend apps must not connect directly to protected infrastructure.

## Current service role

Current backend responsibilities:

- Laravel API runtime.
- Database owner through MySQL.
- Cache/session/queue integration point.
- Commerce/business API foundation through the `marvel/shop` package.
- Storage abstraction through Laravel filesystem configuration.
- Future protected boundary for S3-compatible storage, MinIO/AIStor, and external provider orchestration.

## Current local runtime

The backend Dockerfile runs:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Expected local URL from host browser:

```text
http://localhost:8000
```

Expected URL from frontend containers:

```text
http://backend:8000
```

## Accuracy rule

Do not document planned functionality as current functionality.

Use these labels:

```text
Current
Planned
Not implemented yet
Deprecated
Do not use
```

## Current known gaps

- Dedicated S3-compatible disks are planned but not yet implemented in `config/filesystems.php`.
- MinIO/AIStor Docker Compose service is planned but not yet implemented.
- Upload-slot media endpoints are planned but not yet implemented.
- Dedicated queue worker service is not currently defined in `docker-compose.yml`.
- Current `routes/api.php` only defines the authenticated `/user` route directly in the app route file.
