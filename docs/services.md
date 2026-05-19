# Service Map

This document describes the current services in the Nuvia Beauty `development` branch and their responsibilities.

## Services

| Service | Directory | Package/runtime name | Role | Port |
|---|---|---|---|---:|
| Admin Panel | `admin-panel/` | `@nuvia/admin-panel` | Platform/admin operations UI | `3002` |
| Storefront | `storefront/` | `@nuvia/storefront` | Customer-facing shopping UI | `3003` |
| Backend Engine | `backend-engine/` | `nuvia/beauty-api` | Laravel API, business logic, persistence, integration boundary | `8000` |
| Vendor Portal | `vendor-portal/` | `@nuvia/vendor-portal` | Vendor/seller dashboard UI | `3004` |
| Database | Docker service `db` | MySQL `8.0` | Persistent relational data | `3306` |
| Cache/Queue | Docker service `redis` | Redis `7.4-alpine` | Cache, queue, session backend | `6379` |

## Root workspace

The root `package.json` is a Yarn Classic workspace.

```json
{
  "name": "nuvia-beauty",
  "version": "6.8.0",
  "packageManager": "yarn@1.22.22",
  "workspaces": [
    "admin-panel",
    "storefront",
    "vendor-portal"
  ]
}
```

Backend is not a Yarn workspace package. It is a separate Laravel project under `backend-engine/`.

## Service communication

Current service communication pattern:

```text
admin-panel     -> backend-engine
storefront      -> backend-engine
vendor-portal   -> backend-engine
backend-engine  -> MySQL
backend-engine  -> Redis
backend-engine  -> filesystem / S3-compatible storage
backend-engine  -> external provider APIs
```

Frontend services use `NEXT_PUBLIC_REST_API_ENDPOINT` to reach the backend API.

## Admin Panel

Directory:

```text
admin-panel/
```

Current purpose:

- Admin dashboard UI.
- Platform management interface.
- Operational data views.
- Backend API consumer.

Current stack:

```text
Next.js 15.5.18
React 19.2.6
TypeScript 5.9.3
Tailwind CSS 3.4.14
React Query 3.39.3
Jotai 2.10.1
Axios 1.7.7
```

Current commands:

```bash
yarn dev:admin-panel
yarn build:admin-panel
yarn start:admin-panel
```

## Storefront

Directory:

```text
storefront/
```

Current purpose:

- Customer-facing storefront.
- Product browsing and shopping UI.
- Authentication-aware customer flows.
- PWA-capable frontend through `next-pwa`.

Current stack:

```text
Next.js 15.5.18
React 19.2.6
TypeScript 5.9.3
next-auth 4.24.14
next-pwa 5.6.0
React Query 3.39.3
Jotai 2.10.1
Axios 1.7.7
```

Current commands:

```bash
yarn dev:storefront
yarn build:storefront
yarn start:storefront
```

## Backend Engine

Directory:

```text
backend-engine/
```

Current purpose:

- Laravel API.
- Commerce/business backend.
- Database owner.
- Protected integration boundary for storage and provider APIs.
- Queue/cache/session integration through Redis.

Current stack:

```text
PHP ^8.3
Laravel ^13.0
MySQL
Redis
Guzzle 7.x
league/flysystem-aws-s3-v3 ^3.30
```

Current Docker command:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Vendor Portal

Directory:

```text
vendor-portal/
```

Current purpose:

- Vendor/seller dashboard UI.
- Seller-facing management interface.
- Backend API consumer.

Current stack:

```text
Next.js 15.5.18
React 19.2.6
TypeScript 5.9.3
Tailwind CSS 3.4.14
React Query 3.39.3
Jotai 2.10.1
Axios 1.7.7
```

Current commands:

```bash
yarn dev:vendor-portal
yarn build:vendor-portal
yarn start:vendor-portal
```

## Data flow rule

All protected data must move through the backend.

Correct:

```text
Browser -> Next.js app -> Laravel API -> protected service
```

Incorrect:

```text
Browser -> protected service
```

Protected services include:

```text
MySQL
Redis
S3-compatible private buckets
MinIO / AIStor credentials
Perfect Corp / YouCam API keys
Laravel APP_KEY
Private signed URL generation
```

## Environment ownership

| Variable type | Owner | Browser-visible? |
|---|---|---|
| `NEXT_PUBLIC_*` | Frontend apps | Yes |
| `APP_*` | Backend | No, unless returned by API |
| `DB_*` | Backend/Docker | No |
| `REDIS_*` | Backend/Docker | No |
| `S3_*` / `AWS_*` | Backend/storage layer | No |
| Provider API keys | Backend only | No |

## Documentation rule

If a service responsibility changes, update both:

1. This root service map.
2. The service-specific documentation under `<service>/docs/`.
