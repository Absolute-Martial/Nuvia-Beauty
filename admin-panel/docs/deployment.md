# Admin Panel Deployment

Deployment documentation for `admin-panel`.

## Current service

| Field | Value |
|---|---|
| Directory | `admin-panel/` |
| Compose service | `admin` |
| Default image | `nuvia-beauty-admin-panel:local` |
| Image variable | `NUVIA_ADMIN_IMAGE` |
| Port | `3002` |
| Docker base | `node:24.15.0-alpine` |

## Dockerfile flow

```text
Install Yarn
Install workspace dependencies
Copy repository source
Build /app/admin-panel
Expose 3002
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
docker compose -f docker-compose.yml -f docker-compose.dev.yml up --build admin
```

Or run from root:

```bash
yarn dev:admin-panel
```

Local URL:

```text
http://localhost:3002
```

## Production-like mode

The container starts with:

```bash
yarn start
```

Expected backend API URL from the container network:

```text
http://backend:8000
```

## Deployment checklist

```text
Set backend API endpoint.
Set shop and vendor URLs.
Build image.
Confirm port 3002 is routed.
Confirm admin can reach backend API.
Confirm no backend-only secrets are in NEXT_PUBLIC variables.
```

## Update rule

Update this file when Dockerfile, Compose service, ports, image names, or public runtime variables change.
