# Changelog

This file tracks architecture, implementation, and documentation changes for the Nuvia Beauty `development` branch.

## 2026-05-18

- Added `docs/` directory in root and each service directory (`admin-panel/docs/`, `storefront/docs/`, `backend-engine/docs/`, `vendor-portal/docs/`).
- Added `docs/index.md` as root entry point.
- Initial documentation includes service directories, runtime, tech stack, ports, and workspace info.
- Documented Dockerfile environment, Next.js configs, and image domain allowlists.
- Added future guidance for storage architecture (S3-compatible/MinIO/AIStor) and backend API-mediated media flow.
- Removed old `SERVICE.md` from `admin-panel` and migrated content to structured docs format.