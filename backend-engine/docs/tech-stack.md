# Backend Tech Stack

Current technical stack for `backend-engine` in the Nuvia Beauty `development` branch.

## Runtime baseline

| Area | Current value |
|---|---|
| Service directory | `backend-engine/` |
| Composer package | `nuvia/beauty-api` |
| Runtime | PHP CLI container |
| PHP requirement | `^8.3` |
| Laravel framework | `^13.0` |
| Docker base image | `php:8.3-cli-alpine` |
| Default service port | `8000` |
| Database | MySQL |
| Cache/session/queue service | Redis in Docker Compose |

## Composer dependencies

Current production dependencies from `composer.json`:

| Package | Version constraint | Purpose |
|---|---:|---|
| `laravel/framework` | `^13.0` | Laravel backend framework. |
| `marvel/shop` | `dev-main` | Commerce/shop package loaded from local path repository. |
| `guzzlehttp/guzzle` | `^7.8.2` | HTTP client for external requests. |
| `league/flysystem-aws-s3-v3` | `^3.30` | S3-compatible filesystem support for Laravel disks. |
| `laravel/socialite` | `^5.27` | Social authentication integration. |
| `laravel/tinker` | `^3.0` | Artisan interactive shell. |
| `barryvdh/laravel-dompdf` | `^3.1.2` | PDF generation support. |
| `doctrine/dbal` | `3.7.1` | Database abstraction layer utilities. |
| `messagebird/php-rest-api` | `^4.0.1` | MessageBird API integration. |
| `psr/log` | `3.0.0` | PSR logging interfaces. |
| `stevebauman/purify` | `^6.3.2` | HTML/input purification. |
| `symfony/http-client` | `^7.0` | Symfony HTTP client components. |
| `symfony/mailgun-mailer` | `^7.0` | Mailgun mailer integration. |

## Development dependencies

| Package | Version constraint | Purpose |
|---|---:|---|
| `fakerphp/faker` | `1.21.0` | Test/seed fake data. |
| `laravel/sail` | `^1.58` | Laravel local development tooling. |
| `mockery/mockery` | `1.5.1` | Test mocking. |
| `nunomaduro/collision` | `^8.9.4` | Console exception rendering. |
| `phpunit/phpunit` | `^12.0` | Testing framework. |
| `spatie/laravel-ignition` | `^2.12` | Laravel error page tooling. |
| `squizlabs/php_codesniffer` | `3.7.2` | PHP coding standard checks. |

## Composer path repositories

The backend uses local path repositories for compatibility packages and the shop package:

```text
packages/compat/andersao/l5-repository
packages/compat/prettus/laravel-validation
packages/compat/kodeine/laravel-meta
packages/marvel
```

The `marvel/shop` dependency points to:

```text
backend-engine/packages/marvel
```

## Autoloading

Current PSR-4 autoload map:

```json
{
  "App\\": "app/",
  "Database\\Factories\\": "database/factories/",
  "Database\\Seeders\\": "database/seeders/"
}
```

Current dev autoload map:

```json
{
  "Tests\\": "tests/"
}
```

## Docker image

The backend Dockerfile uses:

```Dockerfile
FROM php:8.3-cli-alpine
```

Installed Alpine packages include:

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

Installed PHP extensions:

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

The Dockerfile copies Composer from:

```Dockerfile
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
```

## Docker build process

Current Dockerfile process:

```text
1. Install system packages and PHP extensions.
2. Copy Composer binary.
3. Set workdir to /var/www/html.
4. Copy composer.json and composer.lock.
5. Copy packages directory.
6. Run composer install with no scripts.
7. Copy backend source code.
8. Create Laravel cache/storage directories.
9. Set storage/cache permissions.
10. Run php artisan package:discover.
11. Expose port 8000.
12. Start artisan serve.
```

Default command:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Docker Compose integration

The root `docker-compose.yml` defines the backend service as:

```text
service: backend
image: ${NUVIA_BACKEND_IMAGE:-nuvia-beauty-backend:local}
port: 8000:8000
network: nuvia-beauty-net
```

The backend depends on:

```text
db
redis
```

## Current filesystem support

Current configured Laravel disks:

```text
local
public
s3
```

Current S3 status:

- S3 adapter dependency exists.
- `config/filesystems.php` still contains a single `s3` disk.
- Dedicated public/private S3-compatible disks are planned but not yet implemented.

## Version update rule

When upgrading backend runtime or dependencies, update this file in the same commit as the dependency change.

Required update targets:

```text
composer.json
composer.lock
backend-engine/Dockerfile
backend-engine/docs/tech-stack.md
backend-engine/docs/deployment.md
root docs if service topology changes
```
