# Deployment

This repository supports three practical runtime modes:

- Local compose dev
- Local compose production-style parity
- GHCR-backed production deployment
- Dokploy-managed production deployment

## Compose Files

- `docker-compose.yml` - shared base services and backend/frontend env
- `docker-compose.dev.yml` - local build/dev overrides, including AIStor parity service
- `docker-compose.production.yml` - production-style AIStor and runtime overrides

## Local Dev

The dev stack includes:

- `db`
- `redis`
- `backend`
- `admin`
- `vendor`
- `storefront`
- `aistor`
- `aistor-setup`

Use:

```bash
APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= \
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build
```

## AIStor / S3-Compatible Storage

The backend is configured for S3-compatible storage using:

- `FILESYSTEM_DISK=s3`
- `MEDIA_DISK=s3`
- `IMPORT_FILESYSTEM_DISK=s3`
- `AISTOR_ENDPOINT`
- `AISTOR_PUBLIC_URL`
- `AISTOR_USE_PATH_STYLE_ENDPOINT=true`

AIStor root credentials and license are mounted from host files. Do not commit real secrets.

## Production

Production compose uses the same AIStor pattern, but you should bind real host paths for:

- `AISTOR_LICENSE_FILE`
- `AISTOR_ROOT_USER_FILE`
- `AISTOR_ROOT_PASSWORD_FILE`
- `AISTOR_DATA_DIR`
- `AISTOR_CERTS_DIR`

Use [`.env.docker.example`](../.env.docker.example) as the starting point for a compose-driven deployment outside Dokploy.

## Dokploy

Dokploy is the recommended UI-driven production host when you want:

- domain mapping in the platform UI
- environment variables stored in Dokploy instead of the repo
- GHCR images pulled at deploy time
- host-mounted AIStor license and credential files

See [docs/dokploy.md](./dokploy.md) for the exact variable set and file layout.

## Ports

- Backend: `8000`
- Admin: `3002`
- Storefront: `3003`
- Vendor: `3004`
- AIStor API: `9000`
- AIStor console: `9001`

## Verification

Recommended checks before pushing changes:

- `docker compose -f docker-compose.yml -f docker-compose.dev.yml config`
- `docker compose -f docker-compose.yml -f docker-compose.production.yml config`
- `php artisan test` in the backend
- `yarn workspace @nuvia/admin-panel build`
- `yarn workspace @nuvia/vendor-portal build`
- `yarn workspace @nuvia/storefront build`
