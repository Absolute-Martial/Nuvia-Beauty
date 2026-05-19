# Admin Panel Documentation

Documentation for the `admin-panel` service in the Nuvia Beauty `development` branch.

## Service identity

| Field | Current value |
|---|---|
| Directory | `admin-panel/` |
| Workspace package | `@nuvia/admin-panel` |
| Package version | `6.8.0` |
| Runtime | Next.js admin dashboard |
| Default port | `3002` |
| Node requirement | `>=24.15.0` |
| Package manager | Yarn Classic via root workspace |

## Documentation files

| File | Purpose |
|---|---|
| `docs/index.md` | Service overview and documentation map. |
| `docs/tech-stack.md` | Framework, dependencies, scripts, Docker runtime, and environment variables. |
| `docs/features-processes.md` | Admin service responsibilities, feature categories, and operational processes. |
| `docs/data-flow-implementation.md` | API data flow, implementation boundaries, and integration rules. |

## Role in Nuvia Beauty

The admin panel is the platform administration dashboard. It is a frontend-only service that consumes the Laravel backend API.

Correct boundary:

```text
admin-panel -> backend-engine -> protected infrastructure
```

The admin panel must not directly access MySQL, Redis, S3-compatible storage, MinIO/AIStor, or provider secrets.

## Local commands

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

## Local URL

```text
http://localhost:3002
```

## Documentation maintenance rule

Update this directory whenever admin package versions, runtime ports, Dockerfile behavior, API endpoint variables, or admin responsibilities change.
