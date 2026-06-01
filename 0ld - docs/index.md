# Documentation Index

Current documentation for the `development` branch of Nuvia Beauty.

## Repository structure

```text
Nuvia-Beauty/
├── admin-panel/      # Admin dashboard frontend
├── backend-engine/   # Laravel API and commerce backend
├── storefront/       # Customer storefront frontend
├── vendor-portal/    # Vendor/seller dashboard frontend
├── docs/             # Root architecture and project-level documentation
├── docker-compose.yml
├── docker-compose.dev.yml
├── package.json
└── yarn.lock
```

## Service directories

| Service | Directory | Runtime | Default port | Documentation |
|---|---|---|---:|---|
| Admin Panel | `admin-panel/` | Next.js dashboard | `3002` | `admin-panel/docs/` |
| Storefront | `storefront/` | Next.js customer storefront | `3003` | `storefront/docs/` |
| Backend Engine | `backend-engine/` | Laravel API | `8000` | `backend-engine/docs/` |
| Vendor Portal | `vendor-portal/` | Next.js vendor dashboard | `3004` | `vendor-portal/docs/` |

## Root documentation map

| File | Purpose |
|---|---|
| `docs/index.md` | Main project documentation entry point. |
| `docs/architecture.md` | Current service architecture, runtime boundaries, and deployment topology. |
| `docs/services.md` | Service-by-service responsibility map and communication model. |
| `docs/storage.md` | Current and target storage direction, including S3-compatible and MinIO/AIStor planning. |
| `docs/changelog.md` | Documentation and architecture change log. |

## Documentation rule

Each service keeps its own documentation inside its own `docs/` directory. Root docs describe cross-service architecture only. Service docs describe service-specific features, process, data flow, tech stack, implementation boundaries, and operational notes.

## Current version baseline

| Area | Current value |
|---|---|
| Root package | `nuvia-beauty` |
| Root version | `6.8.0` |
| Package manager | `yarn@1.22.22` |
| Node requirement | `>=24.15.0` |
| Yarn requirement | `>=1.22.22 <2` |
| Frontend framework | Next.js `15.5.18` |
| Frontend React version | React `19.2.6` |
| Backend framework | Laravel `^13.0` |
| Backend PHP version | PHP `^8.3` |
| Database service | MySQL `8.0` in Docker Compose |
| Cache/queue service | Redis `7.4-alpine` in Docker Compose |

## System rule

Frontend services must use the backend API for protected operations. They must not connect directly to MySQL, Redis, S3, MinIO/AIStor, or external AI provider secrets.

Correct direction:

```text
Browser frontend -> Laravel backend API -> MySQL / Redis / S3-compatible storage / external providers
```
