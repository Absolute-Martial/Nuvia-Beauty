# Vendor Portal Tech Stack

Current stack for `vendor-portal`.

## Runtime

| Field | Value |
|---|---|
| Directory | `vendor-portal/` |
| Package | `@nuvia/vendor-portal` |
| Version | `6.8.0` |
| Runtime | Next.js dashboard |
| Node | `>=24.15.0` |
| Port | `3004` |

## Main versions

| Dependency | Version |
|---|---:|
| Next.js | `15.5.18` |
| React | `19.2.6` |
| React DOM | `19.2.6` |
| TypeScript | `5.9.3` |
| Tailwind CSS | `3.4.14` |
| Axios | `1.7.7` |
| React Query | `3.39.3` |
| Jotai | `2.10.1` |

## Scripts

```bash
yarn dev:vendor-portal
yarn build:vendor-portal
yarn start:vendor-portal
```

## Docker

The Dockerfile uses `node:24.15.0-alpine`, builds `/app/vendor-portal`, exposes `3004`, and starts with `yarn start`.

## Update rule

Update this file when versions, scripts, Docker behavior, or port changes.
