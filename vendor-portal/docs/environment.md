# Vendor Portal Environment

Environment documentation for `vendor-portal`.

## Service identity

| Field | Value |
|---|---|
| Service | `vendor-portal` |
| Package | `@nuvia/vendor-portal` |
| Default port | `3004` |
| Runtime | Next.js |

## Dockerfile defaults

Current `vendor-portal/Dockerfile` sets:

```env
NEXT_TELEMETRY_DISABLED=1
APPLICATION_MODE=production
NEXT_PUBLIC_REST_API_ENDPOINT=http://localhost
NEXT_PUBLIC_SHOP_URL=http://localhost:3003
NEXT_PUBLIC_AUTH_TOKEN_KEY=AUTH_CRED
NEXT_PUBLIC_DEFAULT_LANGUAGE=en
NEXT_PUBLIC_ENABLE_MULTI_LANG=false
NEXT_PUBLIC_AVAILABLE_LANGUAGES=en
```

## Docker Compose variables

Root `docker-compose.yml` sets the `vendor` service variables:

```env
NEXT_PUBLIC_REST_API_ENDPOINT=${NEXT_PUBLIC_REST_API_ENDPOINT:-http://backend:8000}
NEXT_PUBLIC_SHOP_URL=${NEXT_PUBLIC_SHOP_URL:-http://localhost:3003}
NEXT_PUBLIC_VENDOR_URL=${NEXT_PUBLIC_VENDOR_URL:-http://localhost:3004}
```

## Development override

`docker-compose.dev.yml` sets:

```env
NODE_ENV=development
WATCHPACK_POLLING=true
```

## Browser-visible variables

Every `NEXT_PUBLIC_*` variable is visible in the browser bundle.

Use these only for public frontend configuration:

```text
REST API endpoint
shop URL
vendor URL
language settings
public token key name
```

## Backend-only values

Do not place backend-only secrets in vendor frontend variables:

```text
APP_KEY
DB_PASSWORD
S3 secret keys
MinIO/AIStor credentials
provider API keys
```

## Update rule

Update this file when vendor environment variables, Dockerfile defaults, Compose variables, or public runtime config changes.
