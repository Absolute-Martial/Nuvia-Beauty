# Nuvia Beauty Vendor Portal

Private vendor operations dashboard for Nuvia Beauty.

## What this repo is

- Vendor dashboard UI
- Vendor product and shop management surface
- Order, support, and inventory operations surface
- Vendor-facing settings and visibility surface

## How to run it locally

```bash
yarn install
yarn dev
```

The vendor app runs on port `3004` by default.

## How to build it

```bash
yarn build
```

## How to test it

```bash
yarn lint
yarn build
```

Then smoke test login, dashboard access, protected routes, product management,
inventory, order handling, and settings visibility.

## How to deploy it

- Deploy separately from the public storefront
- Protect with application auth
- Put Cloudflare Access or equivalent in front of public environments
- Do not expose vendor routes through public storefront tunnels
