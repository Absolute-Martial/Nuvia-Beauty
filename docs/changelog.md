# Changelog

This file tracks architecture, implementation, and documentation changes for the Nuvia Beauty `development` branch.

## 2026-05-18

- Added `docs/` directory in root and each service directory (`admin-panel/docs/`, `storefront/docs/`, `backend-engine/docs/`, `vendor-portal/docs/`).
- Added `docs/index.md` as root entry point.
- Initial documentation includes service directories, runtime, tech stack, ports, and workspace info.
- Documented Dockerfile environment, Next.js configs, and image domain allowlists.
- Added future guidance for storage architecture (S3-compatible/MinIO/AIStor) and backend API-mediated media flow.
- Removed old `SERVICE.md` from `admin-panel` and migrated content to structured docs format.

## 2026-05-28

- Implemented the Phase 4 S3-compatible storage foundation in `backend-engine/config/filesystems.php` with `s3_public`, `s3_beauty_inputs`, `s3_beauty_results`, and `s3_beauty_calibration`.
- Added Phase 4 storage metadata and cleanup domain code under `backend-engine/app/Domains/Storage/`.
- Added Phase 4 beauty mapping and deterministic recommendation domain code under `backend-engine/app/Domains/Beauty/`.
- Added new backend migrations for `beauty_media_assets`, `beauty_product_mappings`, and `beauty_recommendations`.
- Added app-level `/api/v1/storage/*` and `/api/v1/beauty/*` route files.
- Added a controlled `BeautyProductMappingSeeder` workflow for the first 10 existing products.
- Added storefront recommendation UI components with score, confidence, reasons, warnings, loading, error, and empty states.
- Updated root and backend documentation to reflect the current Phase 4 implementation and the controlled admin/vendor mapping workflow.
- Added Epic 7 beauty event capture and product signal aggregation with admin recompute support.
- Added inline admin and vendor beauty mapping editors on existing product edit screens.
- Added an admin beauty mapping overview surface with mapped/unmapped counts, recommendation-readiness status, and mapped-product recompute controls.
- Updated vendor beauty mapping status presentation to distinguish missing, partial, and recommendation-ready products.
- Added the Phase 4 seller consultation backend foundation with `beauty_profiles`, `beauty_profile_snapshots`, `beauty_sessions`, `beauty_ai_tasks`, `beauty_analysis_results`, `beauty_quota_accounts`, `beauty_quota_events`, and lightweight `audit_logs`.
- Added authenticated `/api/v1/beauty/sessions/*` consultation routes for session creation, private media attachment, deterministic recommendation generation, and save/discard state transitions.
- Added a vendor consultation page at `vendor-portal/src/pages/beauty/consultations.tsx` for seller-assisted beauty sessions using the existing private storage flow.
