# Storefront Documentation

Documentation for the `storefront` service.

## Service identity

| Field | Value |
|---|---|
| Directory | `storefront/` |
| Package | `@nuvia/storefront` |
| Version | `6.8.0` |
| Runtime | Next.js customer storefront |
| Node | `>=24.15.0` |
| Port | `3003` |

## Documentation files

| File | Purpose |
|---|---|
| `docs/index.md` | Storefront documentation entry point. |
| `docs/tech-stack.md` | Runtime, package versions, scripts, and Docker notes. |
| `docs/features-processes.md` | Customer-facing features and process flow. |
| `docs/data-flow-implementation.md` | API, state, image, and PWA data flow. |
| `docs/environment.md` | Public environment variables and runtime config. |
| `docs/deployment.md` | Build, start, Docker, and Compose deployment notes. |
| `docs/maintenance.md` | Update rules and known limitations. |

## Current role

`storefront` is the customer-facing shopping frontend. It consumes `backend-engine` through the configured REST API endpoint.

Correct boundary:

```text
storefront -> backend-engine -> protected services
```

## Local URL

```text
http://localhost:3003
```

## Root commands

```bash
yarn dev:storefront
yarn build:storefront
yarn start:storefront
```

## Maintenance rule

Update this docs directory when storefront scripts, runtime, environment variables, customer flows, image loading, PWA behavior, or API flow changes.
