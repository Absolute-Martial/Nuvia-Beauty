# Admin Panel Tech Stack

Current technical stack for `admin-panel`.

## Runtime

| Field | Value |
|---|---|
| Directory | `admin-panel/` |
| Package | `@nuvia/admin-panel` |
| Version | `6.8.0` |
| Runtime | Next.js admin dashboard |
| Node | `>=24.15.0` |
| Port | `3002` |
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
| next-i18next | `16.0.6` |
| i18next | `26.1.0` |
| react-i18next | `17.0.7` |
| ESLint | `8.57.1` |
| eslint-config-next | `15.5.18` |

## Scripts

From repository root:

```bash
yarn dev:admin-panel
yarn build:admin-panel
yarn start:admin-panel
```

From `admin-panel/`:

```bash
yarn dev
yarn build
yarn start
yarn lint
```

## Current script behavior

| Script | Behavior |
|---|---|
| `dev` | Runs Next dev on port `3002` with polling and Webpack. |
| `build` | Runs `next build`. |
| `start` | Runs `next start` on port `3002`. |
| `lint` | Runs ESLint with zero warnings allowed. |

## Docker behavior

The Dockerfile installs Yarn, installs workspace dependencies, builds from `/app/admin-panel`, exposes `3002`, and starts with `yarn start`.

## Current Next.js config notes

- `reactStrictMode` is enabled.
- `outputFileTracingRoot` points to the repository root.
- i18n config is loaded from `next-i18next.config`.
- PWA code exists in comments but is not active.
- Image domains are statically allowlisted.
- TypeScript build errors are ignored only when `APPLICATION_MODE=production`.

## Maintenance rule

Update this file when package versions, scripts, Dockerfile behavior, port, or Next.js configuration changes.
