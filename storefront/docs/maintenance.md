# Storefront Maintenance

Maintenance documentation for `storefront`.

## Current baseline

| Field | Value |
|---|---|
| Directory | `storefront/` |
| Package | `@nuvia/storefront` |
| Version | `6.8.0` |
| Node | `>=24.15.0` |
| Next.js | `15.5.18` |
| React | `19.2.6` |
| Port | `3003` |

## Maintenance commands

From repository root:

```bash
yarn dev:storefront
yarn build:storefront
yarn start:storefront
```

From `storefront/`:

```bash
yarn lint
yarn build
yarn clean
```

## Update checklist

When changing storefront code, check:

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

Update `docs/deployment.md` when port, image name, Dockerfile behavior, PWA behavior, or Compose service behavior changes.

## API flow rule

Update `docs/data-flow-implementation.md` when API request flow, rewrites, image loading, PWA flow, or frontend state flow changes.

## Known current limitations

```text
Image domains are statically allowlisted.
Development command uses Webpack explicitly.
TypeScript build errors are ignored in current Next config.
/api-backend rewrite points to 127.0.0.1:8000 and should be reviewed for deployment.
```

## Documentation rule

Do not document planned storefront functionality as current functionality. Mark unfinished items as `Planned` or `Not implemented yet`.
