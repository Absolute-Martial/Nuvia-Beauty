# System Architecture

Current architecture documentation for the Nuvia Beauty `development` branch.

## High-level layout

```text
Nuvia-Beauty/
├── admin-panel/      # Admin dashboard frontend
├── backend-engine/   # Laravel backend/API service
├── storefront/       # Customer-facing storefront frontend
├── vendor-portal/    # Vendor-facing dashboard frontend
├── docs/             # Root project architecture docs
├── docker-compose.yml
└── docker-compose.dev.yml
```

## Runtime topology

```text
Browser
  ├── Admin UI      -> admin-panel    -> Laravel API
  ├── Storefront    -> storefront     -> Laravel API
  └── Vendor Portal -> vendor-portal  -> Laravel API

Laravel API -> MySQL
Laravel API -> Redis
Laravel API -> filesystem / S3-compatible storage
Laravel API -> external provider APIs
```

## Docker Compose services

| Compose service | Directory | Runtime | Exposed port |
|---|---|---|---:|
| `backend` | `backend-engine/` | PHP 8.3 / Laravel | `8000` |
| `admin` | `admin-panel/` | Node 24.15 / Next.js | `3002` |
| `storefront` | `storefront/` | Node 24.15 / Next.js | `3003` |
| `vendor` | `vendor-portal/` | Node 24.15 / Next.js | `3004` |
| `db` | MySQL container | MySQL 8.0 | `3306` |
| `redis` | Redis container | Redis 7.4 Alpine | `6379` |

## Current backend environment map

The backend service receives shared runtime variables through the `x-backend-env` block in `docker-compose.yml`.

Important current backend variables:

```env
APP_NAME=Nuvia Beauty
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379
FRONTEND_URL=http://localhost:3003
ADMIN_URL=http://localhost:3002
VENDOR_URL=http://localhost:3004
```

## Current frontend environment map

Frontend services consume the backend through browser-visible `NEXT_PUBLIC_*` variables.

```env
NEXT_PUBLIC_REST_API_ENDPOINT=http://backend:8000
NEXT_PUBLIC_SHOP_URL=http://localhost:3003
NEXT_PUBLIC_VENDOR_URL=http://localhost:3004
NEXT_PUBLIC_SITE_URL=http://localhost:3003
```

## Data ownership boundaries

| Data or operation | Owner |
|---|---|
| Products, users, orders, settings, backend validation | `backend-engine` |
| Admin dashboard UI state | `admin-panel` |
| Storefront browsing and customer interaction UI | `storefront` |
| Vendor dashboard UI state | `vendor-portal` |
| Persistent database records | MySQL through `backend-engine` |
| Queue/cache/session runtime | Redis through `backend-engine` |
| Public/private media storage | Backend-mediated filesystem/S3-compatible storage |

## Security boundary

Frontend services must not receive backend-only credentials.

Forbidden in frontend code and `NEXT_PUBLIC_*` variables:

```text
Database credentials
Redis credentials
S3/MinIO/AIStor access keys
Perfect Corp / YouCam private API keys
Laravel APP_KEY
Long-lived private media URLs
```

Correct pattern:

```text
Frontend -> backend API -> protected services
```

Incorrect pattern:

```text
Frontend -> MySQL / Redis / MinIO / Perfect Corp directly
```

## Current development mode

`docker-compose.dev.yml` builds the backend and frontend images locally and mounts source directories into containers for development.

Development commands:

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up --build
```

Frontend services run their `yarn dev` commands. Backend runs:

```bash
php artisan serve --host 0.0.0.0 --port 8000
```

## Current production-like mode

`docker-compose.yml` expects prebuilt or locally tagged service images:

```text
nuvia-beauty-backend:local
nuvia-beauty-admin-panel:local
nuvia-beauty-storefront:local
nuvia-beauty-vendor-portal:local
```

Each frontend Dockerfile builds the workspace package and starts with `yarn start`.

## Architecture direction

The architecture should remain modular:

```text
admin-panel    = admin UI only
storefront     = customer UI only
vendor-portal  = seller/vendor UI only
backend-engine = data, validation, protected integration, storage, provider orchestration
```

New protected features should be added to the backend first, then consumed by frontend apps through typed or clearly documented API clients.
