# Storefront Deployment

Deployment documentation for `storefront`.

## Current service

| Field | Value |
|---|---|
| Directory | `storefront/` |
| Compose service | `storefront` |
| Default image | `nuvia-beauty-storefront:local` |
| Image variable | `NUVIA_STOREFRONT_IMAGE` |
| Port | `3003` |
| Docker base | `node:24.15.0-alpine` |

## Dockerfile flow

```text
Install Yarn
Install workspace dependencies
Copy repository source
Build /app/storefront
Expose 3003
Run yarn start
```

## Compose environment

```env
NEXT_PUBLIC_REST_API_ENDPOINT=http://backend:8000
NEXT_PUBLIC_SITE_URL=http://localhost:3003
```

## Development mode

Use root Compose files:

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up --build storefront
```

Or run from root:

```bash
yarn dev:storefront
```

Local URL:

```text
http://localhost:3003
```

## Production-like mode

The container starts with:

```bash
yarn start
```

Expected backend API URL from container network:

```text
http://backend:8000
```

## Deployment checklist

```text
Set backend API endpoint.
Set public site URL.
Build image.
Confirm port 3003 is routed.
Confirm storefront can reach backend API.
Confirm PWA behavior for non-development build.
Confirm no backend-only secrets are in NEXT_PUBLIC variables.
```

## Update rule

Update this file when Dockerfile, Compose service, ports, image names, PWA build behavior, or public runtime variables change.
