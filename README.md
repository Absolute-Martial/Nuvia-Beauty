# Nuvia Beauty

Nuvia Beauty is a multivendor AI beauty commerce platform built for the DevNetwork [AI + ML] Hackathon 2026.
It keeps the marketplace structure of the original commerce base while focusing the experience on beauty,
personalization, and photo-based try-on workflows.

Project page:

- https://devnetwork-ai-ml-hack-2026.devpost.com/

## What It Does

- Lets shoppers browse a multivendor catalog with vendor identity preserved
- Supports product discovery for beauty, fashion, skin, hair, and accessory use cases
- Uses AI/AR-assisted try-on and recommendation flows to improve buying confidence
- Saves customer-facing shopping context so future purchases can be more personalized
- Keeps the admin, vendor, storefront, and backend surfaces separate

## Submission Snapshot

This repository is the working hackathon submission. It is in a tested, public-phase ready state for demo and review.

## Stack

- Backend: PHP 8.3, Laravel 13
- Admin panel: Next.js 15.5.18, React 19.2.6, port 3002
- Vendor portal: Next.js 15.5.18, React 19.2.6, port 3004
- Storefront: Next.js 15.5.18, React 19.2.6, port 3003
- Package manager: Yarn 1.22.22
- Storage: S3-compatible object storage with MinIO / AIStor support

## Local Development

Install dependencies and run the apps:

```bash
yarn install
```

Backend API:

```bash
cd backend-engine
php artisan serve --host 0.0.0.0 --port 8000
```

Admin panel:

```bash
yarn workspace @nuvia/admin-panel dev
```

Vendor portal:

```bash
yarn workspace @nuvia/vendor-portal dev
```

Storefront:

```bash
yarn workspace @nuvia/storefront dev
```

Default ports:

- Backend API: `8000`
- Admin: `3002`
- Storefront: `3003`
- Vendor portal: `3004`

## Docker Compose

Development:

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build
```

Production-style deployment:

```bash
docker compose -f docker-compose.yml -f docker-compose.production.yml up -d --build
```

## Environment

Use the example files as the source of truth:

- [`.env.docker.example`](./.env.docker.example)
- [`.env.dokploy.example`](./.env.dokploy.example)

The backend uses AIStor-compatible S3 storage settings, and the frontend apps only receive browser-safe values.

## Docs

- [Project docs](./docs/README.md)
- [Backend engine docs](./backend-engine/docs/README.md)
- [Admin panel docs](./admin-panel/docs/README.md)
- [Vendor portal docs](./vendor-portal/docs/README.md)
- [Storefront docs](./storefront/docs/README.md)
- [Submission notes](./SUBMISSION.md)

## CI and Release

- GitHub Actions validates workflows, backend tests, frontend lint/build, and Docker image definitions
- Docker images are published separately to GHCR
- Wiki publishing is driven from the docs folders in this repo

## Hackathon Notes

- The public-facing demo should focus on the multivendor shopping flow
- Try-on, personalization, and vendor-preserving product discovery are the key user-facing behaviors
- Keep the README aligned with the actual repo structure and deployed surfaces
