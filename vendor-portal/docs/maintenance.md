# Vendor Portal Maintenance

Maintenance documentation for `vendor-portal`.

## Current baseline

| Field | Value |
|---|---|
| Directory | `vendor-portal/` |
| Package | `@nuvia/vendor-portal` |
| Version | `6.8.0` |
| Node | `>=24.15.0` |
| Next.js | `15.5.18` |
| React | `19.2.6` |
| Port | `3004` |

## Maintenance commands

From repository root:

```bash
yarn dev:vendor-portal
yarn build:vendor-portal
yarn start:vendor-portal
```

From `vendor-portal/`:

```bash
yarn lint
yarn build
```

## Update checklist

When changing vendor portal code, check:

```text
package.json
next.config.js
Dockerfile
docs/tech-stack.md
docs/environment.md
docs/deployment.md
docs/features-processes.md
docs/data-flow-implementation.md
```

## Dependency rule

Update `docs/tech-stack.md` when package versions, scripts, or runtime requirements change.

## Environment rule

Update `docs/environment.md` when Dockerfile defaults, Compose variables, or public config variables change.

## Deployment rule

Update `docs/deployment.md` when port, image name, Dockerfile behavior, or Compose service behavior changes.

## API flow rule

Update `docs/data-flow-implementation.md` when API request flow, API client structure, image loading, or frontend state flow changes.

## Known current limitations

```text
PWA config is present but commented out.
Image domains are statically allowlisted.
Development command uses Webpack explicitly.
Production mode may ignore TypeScript build errors through APPLICATION_MODE=production.
```

## Documentation rule

Do not document planned vendor functionality as current functionality. Mark unfinished items as `Planned` or `Not implemented yet`.
