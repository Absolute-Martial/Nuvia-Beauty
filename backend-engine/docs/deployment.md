# Backend Deployment

Deployment documentation for `backend-engine` in the Nuvia Beauty `development` branch.

## Current service identity

| Field | Current value |
|---|---|
| Service directory | `backend-engine/` |
| Compose service | `backend` |
| Default image | `nuvia-beauty-backend:local` |
| Image variable | `NUVIA_BACKEND_IMAGE` |
| Runtime | PHP 8.3 CLI Alpine |
| Framework | Laravel `^13.0` |
| Exposed port | `8000` |
| Compose network | `nuvia-beauty-net` |

## Current Dockerfile

The backend Dockerfile uses:

```Dockerfile
FROM php:8.3-cli-alpine
```

The image installs:

```text
bash
curl
git
icu-dev
libpng-dev
libzip-dev
libxml2-dev
oniguruma-dev
freetype-dev
libjpeg-turbo-dev
$PHPIZE_DEPS
```

The image installs these PHP extensions:

```text
bcmath
exif
gd
intl
mbstring
pdo_mysql
zip
xml
```

Composer is copied from:

```Dockerfile
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
```

## Current image build process

Current Dockerfile flow:

```text
1. Install Alpine packages and PHP extensions.
2. Copy Composer binary.
3. Set workdir to /var/www/html.
4. Copy composer.json and composer.lock.
5. Copy packages directory.
6. Run composer install --no-interaction --no-progress --prefer-dist --optimize-autoloader --no-scripts.
7. Copy backend source.
8. Create Laravel cache and storage directories.
9. Apply write permissions to bootstrap/cache and storage.
10. Run php artisan package:discover --ansi.
11. Expose port 8000.
12. Start php artisan serve.
```

Current default command:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Current Docker Compose deployment

Root `docker-compose.yml` defines:

```yaml
backend:
  image: ${NUVIA_BACKEND_IMAGE:-nuvia-beauty-backend:local}
  restart: unless-stopped
  depends_on:
    - db
    - redis
  ports:
    - "8000:8000"
  networks:
    - nuvia_beauty_net
```

Backend environment is injected through:

```yaml
x-backend-env: &backend_env
```

## Current development deployment

`docker-compose.dev.yml` overrides the backend service to build locally:

```yaml
backend:
  build:
    context: ./backend-engine
    dockerfile: Dockerfile
    network: host
  command: php artisan serve --host 0.0.0.0 --port 8000
  environment:
    APP_ENV: local
  volumes:
    - ./backend-engine:/var/www/html
    - backend_vendor:/var/www/html/vendor
```

Run development stack:

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up --build
```

Backend URL from host:

```text
http://localhost:8000
```

Backend URL from frontend containers:

```text
http://backend:8000
```

## Required production environment

Current Compose requires:

```env
APP_KEY
```

Because Compose uses:

```env
APP_KEY=${APP_KEY:?APP_KEY is required}
```

Minimum production-like backend variables:

```env
APP_NAME=Nuvia Beauty
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.example.com
APP_KEY=base64:...
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nuvia_beauty
DB_USERNAME=nuvia_beauty
DB_PASSWORD=...
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379
FRONTEND_URL=https://www.example.com
ADMIN_URL=https://admin.example.com
VENDOR_URL=https://vendor.example.com
```

## Storage permissions

The Dockerfile creates and grants write permission to:

```text
bootstrap/cache
storage/framework/cache/data
storage/framework/sessions
storage/framework/views
storage/logs
```

These directories must remain writable by the runtime user/group.

## Laravel deployment commands

Useful commands after deployment or environment changes:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

If using production config caching later:

```bash
php artisan config:cache
php artisan route:cache
```

Run migrations when schema changes are deployed:

```bash
php artisan migrate --force
```

## Queue worker deployment

Current status:

```text
Dedicated queue worker service is not implemented in docker-compose.yml.
```

Planned worker service:

```bash
php artisan queue:work redis --sleep=3 --tries=3 --timeout=120
```

Do not set `QUEUE_CONNECTION=redis` in a production workflow without ensuring a worker process is running.

## S3-compatible storage deployment

Current status:

```text
S3 adapter dependency exists.
Dedicated S3-compatible disks are not implemented yet.
MinIO/AIStor Compose service is not implemented yet.
```

Planned production direction:

```text
backend-engine -> S3-compatible endpoint -> MinIO AIStor / AWS S3 / compatible object store
```

Storage credentials must be backend-only.

## Reverse proxy expectation

Current backend serves HTTP on port `8000` internally. Production should place a reverse proxy/CDN/WAF in front of public HTTP traffic.

Typical public routing:

```text
https://api.example.com -> reverse proxy -> backend:8000
```

Admin/vendor/private operational surfaces should be protected separately where required.

## Deployment checklist

Before deploying backend:

```text
Set APP_KEY.
Set APP_ENV and APP_DEBUG correctly.
Set DB credentials.
Set Redis settings if using Redis cache/session/queue.
Verify storage settings.
Verify provider secrets are backend-only.
Run composer install/build image.
Run migrations.
Clear/rebuild Laravel caches.
Confirm /api endpoint availability.
Check storage/logs is writable.
Confirm queue worker exists if QUEUE_CONNECTION is not sync.
```

## Update rule

Update this file when:

```text
Dockerfile changes
Compose backend service changes
ports change
runtime image changes
deployment command changes
queue worker service is added
storage service is added
required environment variables change
```
