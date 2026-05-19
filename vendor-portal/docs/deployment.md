# Vendor Portal Deployment

Deployment documentation for `vendor-portal`.

## Current service

| Field | Value |
|---|---|
| Directory | `vendor-portal/` |
| Compose service | `vendor` |
| Default image | `nuvia-beauty-vendor-portal:local` |
| Image variable | `NUVIA_VENDOR_IMAGE` |
| Port | `3004` |
| Docker base | `node:24.15.0-alpine` |

## Dockerfile flow

```text
Install Yarn
Install workspace dependencies
Copy repository source
Build /app/vendor-portal
Expose 3004
Run yarn start
```

## Compose environment

```env
NEXT_PUBLIC_REST_API_ENDPOINT=http://backend:8000
NEXT_PUBLIC_SHOP_URL=http://localhost:3003
NEXT_PUBLIC_VENDOR_URL=http://localhost:3004
```

## Development mode

Use root Compose files:

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up --build vendor
```

Or run from root:

```bash
yarn dev:vendor-portal
```

Local URL:

```text
http://localhost:3004
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
Set shop and vendor URLs.
Build image.
Confirm port 3004 is routed.
Confirm vendor portal can reach backend API.
Confirm no backend-only secrets are in NEXT_PUBLIC variables.
```

## Update rule

Update this file when Dockerfile, Compose service, ports, image names, or public runtime variables change.
