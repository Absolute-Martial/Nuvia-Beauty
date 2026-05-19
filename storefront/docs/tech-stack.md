# Storefront Tech Stack

Current technical stack for `storefront`.

## Runtime

| Field | Value |
|---|---|
| Directory | `storefront/` |
| Package | `@nuvia/storefront` |
| Version | `6.8.0` |
| Runtime | Next.js customer storefront |
| Node | `>=24.15.0` |
| Port | `3003` |
| Docker base | `node:24.15.0-alpine` |

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
| next-auth | `4.24.14` |
| next-pwa | `5.6.0` |
| next-i18next | `16.0.6` |
| i18next | `26.1.0` |
| react-i18next | `17.0.7` |
| ESLint | `8.57.1` |
| eslint-config-next | `15.5.18` |

## Scripts

From repository root:

```bash
yarn dev:storefront
yarn build:storefront
yarn start:storefront
```

From `storefront/`:

```bash
yarn dev
yarn build
yarn start
yarn lint
yarn clean
```

## Current script behavior

| Script | Behavior |
|---|---|
| `dev` | Runs Next dev on port `3003` with polling and Webpack. |
| `build` | Runs `next build`. |
| `start` | Runs `next start` on port `3003`. |
| `lint` | Runs ESLint with zero warnings allowed. |
| `clean` | Removes `node_modules`, `.next`, and `.cache`. |

## Docker behavior

The Dockerfile installs Yarn, installs workspace dependencies, builds from `/app/storefront`, exposes `3003`, and starts with `yarn start`.

## Current Next.js config notes

- `next-pwa` is active outside development mode.
- `outputFileTracingRoot` points to the repository root.
- i18n config is loaded from `next-i18next.config`.
- A rewrite maps `/api-backend/:path*` to `http://127.0.0.1:8000/:path*`.
- Image domains are statically allowlisted.
- TypeScript build errors are currently ignored.

## Maintenance rule

Update this file when package versions, scripts, Dockerfile behavior, port, or Next.js configuration changes.
