# Nuvia Beauty Backend

Laravel API, auth, database, workers, storage, and AI gateway for Nuvia Beauty.

## What this repo is

- Laravel backend API
- Sanctum authentication
- Product, vendor, shop, cart, and marketplace APIs
- YouCam virtual try-on proxy
- Private file handling
- Queue and worker runtime

## How to run it locally

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8000
```

## How to build it

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
```

## How to test it

```bash
php artisan test
```

Also smoke test `/settings`, `/products`, `/categories`, `/shops`, `/token`,
and the try-on upload/task/poll routes.

## How to deploy it

- Set real secrets in the deployment environment
- Run migrations through the release process
- Start the web process and queue workers separately
- Keep AI provider keys and storage credentials server-side only
