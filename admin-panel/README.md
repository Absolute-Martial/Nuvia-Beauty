# Nuvia Beauty Admin

Private platform administration dashboard for Nuvia Beauty.

## What this repo is

- Admin dashboard UI
- Platform moderation surface
- Vendor approval and oversight surface
- Support, feature status, and audit visibility surface

## How to run it locally

```bash
yarn install
yarn dev
```

The admin app runs on port `3002` by default.

## How to build it

```bash
yarn build
```

## How to test it

```bash
yarn lint
yarn build
```

Then smoke test login, dashboard access, protected routes, product moderation,
vendor management, and settings visibility.

## How to deploy it

- Deploy separately from the public storefront
- Protect with application auth
- Put Cloudflare Access or equivalent in front of public environments
- Do not expose admin routes through public storefront tunnels
