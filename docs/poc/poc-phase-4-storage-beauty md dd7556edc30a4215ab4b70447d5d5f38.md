# poc-phase-4-storage-beauty.md

Owner: Susank Shakya

<aside>
🧪

**`docs/poc/poc-phase-4-storage-beauty.md`** · PoC 12 — proving the S3-compatible storage foundation and first beauty product intelligence layer (Phase 4).

</aside>

The canonical spec lives in Technical Papers: [PoC 12 — Storage & Beauty Intelligence Foundation (Phase 4)](https://app.notion.com/p/PoC-12-Storage-Beauty-Intelligence-Foundation-Phase-4-0b6ab14688844db88c69148cff827930?pvs=21), indexed in [PoC Specifications](https://app.notion.com/p/PoC-Specifications-36ff29d2a6b181e1bd14d17ff0790d56?pvs=21). This page is the docs-aligned, self-contained version.

# 1. Executive summary

Nuvia Beauty is being advanced from a deployed base commerce app into a beauty-focused commerce and consultation platform. The base deployment already exposes the core runtime surfaces (storefront, admin-panel, vendor-portal, backend API, and S3/MinIO-compatible endpoints). This PoC turns that base into a structured, push-ready foundation by implementing S3-compatible media storage and the first beauty product intelligence layer — the foundation that must exist before AI can be trusted: storage boundaries, media ownership, product mappings, recommendation rules, admin/vendor controls, and clear documentation.

# 2. Current deployment context

| Surface | Purpose | Port |
| --- | --- | --- |
| Storefront | Customer-facing shop | `:3003` |
| Admin Panel | Platform/admin dashboard | `:3002` |
| Vendor Portal | Seller/vendor dashboard | `:3004` |
| Backend API | Laravel backend API | `:8000` |
| S3 Assets Endpoint | S3-compatible object API | `:9000` |
| S3 Console Endpoint | Storage console/admin UI | `:9001` |

The deployed environment uses HTTPS, Let's Encrypt certificates, and Cloudflare-backed domains.

# 3. Objectives

## 3.1 Primary objectives

1. Implement an S3-compatible storage architecture (MinIO/AIStor, portable to AWS S3).
2. Separate public media from private beauty/customer media.
3. Create backend-controlled media upload and access flows.
4. Add beauty-specific product metadata and mapping capabilities.
5. Implement deterministic recommendation scoring with reason and warning cards.
6. Prepare the backend for future AI/provider orchestration without exposing secrets or private media.
7. Keep documentation accurate, current, and separated by service.

## 3.2 Engineering guardrails

- No frontend storage credentials; no hardcoded MinIO-only logic in business code.
- No raw private media stored in MySQL; no undocumented route or data-flow changes.
- No planned features described as already implemented.

# 4. Scope

## 4.1 In scope

- **Storage foundation** — S3-compatible config; MinIO/AIStor env; public bucket; private input + result buckets; optional calibration bucket; media metadata table; upload-slot, confirm, private-download, delete/discard endpoints; retention/expiry job.
- **Beauty intelligence** — beauty product attributes; concern / skin-type / tone / undertone / ingredient / avoid tags; mapping table; deterministic scoring; reason + warning cards; admin/vendor mapping support where practical.
- **Documentation** — root PoC docs, service docs, storage checklist, current-vs-planned labels, API contract docs.

## 4.2 Out of scope

- Full customer self-scan; full Perfect Corp / YouCam live workflow; makeup VTO as a required feature; hair/jewelry try-on.
- Native mobile app; subscription billing; advanced analytics dashboard; cross-profile ML engine; clinical/medical wording; social sharing.

## 4.3 Constraints

- Preserve the deployable-app layout — one backend API (`backend-engine`) plus three frontend clients (`storefront`, `admin-panel`, `vendor-portal`); Laravel remains the backend authority for protected operations; frontends stay browser-facing clients only. Backend domains are Logical Modules, not services.
- Storage stays S3-compatible (not MinIO-hardcoded); private beauty media must never become public assets; AI/provider keys remain backend-only.

# 5. Technical requirements

## 5.1 Backend structure

```
backend-engine/app/Domains/Storage/
├── Controllers/
├── DTO/
├── Jobs/
├── Policies/
└── Services/

backend-engine/app/Domains/Beauty/
├── Controllers/
├── DTO/
├── Models/
├── Services/
└── Policies/
```

## 5.2 Storage model

| Bucket | Visibility | Purpose |
| --- | --- | --- |
| `nuvia-public-assets` | Public/CDN | Product images, shop logos, banners, public UI assets. |
| `nuvia-private-beauty-inputs` | Private | Customer/source photos or future scan inputs. |
| `nuvia-private-beauty-results` | Private | Generated result media, overlays, future try-on outputs. |
| `nuvia-private-calibration` | Private | Explicit opt-in calibration images (later phase). |

```
STORAGE_DRIVER=s3_compatible
S3_PROVIDER=minio_aistor
S3_USE_PATH_STYLE_ENDPOINT=true
S3_PUBLIC_BUCKET=nuvia-public-assets
S3_BEAUTY_INPUTS_BUCKET=nuvia-private-beauty-inputs
S3_BEAUTY_RESULTS_BUCKET=nuvia-private-beauty-results
S3_BEAUTY_CALIBRATION_BUCKET=nuvia-private-calibration
S3_UPLOAD_URL_TTL_MINUTES=15
S3_DOWNLOAD_URL_TTL_MINUTES=60
```

## 5.3 Database

Minimum new tables: `beauty_media_assets`, `beauty_product_mappings`, `beauty_recommendations`.

```
beauty_recommendations:
id, customer_id?, profile_id?, session_id?, product_id, score, confidence,
reasons_json, warnings_json, accepted?, dismissed?, created_at, updated_at
```

## 5.4 APIs

```
POST   /api/v1/storage/upload-slots
POST   /api/v1/storage/media/{mediaId}/confirm
GET    /api/v1/storage/media/{mediaId}/download-url
DELETE /api/v1/storage/media/{mediaId}
GET    /api/v1/beauty/recommendations
POST   /api/v1/beauty/recommendations/generate
GET    /api/v1/beauty/product-mappings
POST   /api/v1/beauty/product-mappings
PUT    /api/v1/beauty/product-mappings/{id}
```

## 5.5 Security & access

- Frontends never receive storage or provider keys; private media never lands in public buckets.
- Signed URLs are short-lived; the backend validates ownership before private access and validates file type/size before issuing an upload slot.
- The backend never logs full signed URLs or secrets.

# 6. Success criteria & KPIs

| KPI | PoC target |
| --- | --- |
| Upload slot creation latency | Under 500 ms (excl. network). |
| Signed upload TTL | 15 minutes default. |
| Signed download TTL | 60 minutes default. |
| Recommendation generation latency | Under 1 second (seeded dataset). |
| Public/private storage separation | 100% pass in manual verification. |
| Secret exposure in browser | 0 findings. |
| Seed product coverage | At least 10 beauty products. |
| Recommendation output coverage | At least 3 products per eligible request. |

# 7. Timeline & milestones

| Milestone | Estimate | Outcome |
| --- | --- | --- |
| M1 Baseline verification | 0.5–1d | Confirm services/ports; docs match deployed; start changelog. |
| M2 Storage configuration | 1–2d | Env vars, public/private disks, MinIO/AIStor endpoint, docs. |
| M3 Media upload flow | 2–3d | Metadata migration, storage service, upload-slot/confirm/download, delete/discard. |
| M4 Beauty product mapping | 2–3d | Mapping table/model, seed data, admin/vendor workflow, attributes in payloads. |
| M5 Recommendation engine | 2–3d | Scoring service, reason/warning builders, endpoint, storefront rendering. |
| M6 Verification & push readiness | 1–2d | Manual QA, secret + storage checks, seed verify, docs/changelog, push summary. |

Solo build order: backend storage → backend mappings → recommendation endpoint → storefront display → vendor/admin support → QA/docs.

# 8. Risks

| Risk | Impact | Mitigation |
| --- | --- | --- |
| S3 endpoint misconfiguration | Uploads fail | Env-driven config; verify disks separately. |
| Private media accidentally public | High | Separate buckets; test public-access denial. |
| Frontend receives storage credentials | High | Backend-only env; browser network check. |
| MinIO-specific code blocks portability | Medium | Code against the Laravel/Flysystem abstraction. |
| Recommendation feels weak | Medium | Clear reason cards + seeded beauty tags. |
| Scope expands into AI early | High | Keep AI/provider live calls out of PoC scope. |
| Docs drift | Medium | Update docs in the same commit. |

# 9. Acceptance checklist

- [ ]  Backend runs with configured storage env.
- [ ]  Public bucket serves product/shop assets.
- [ ]  Private bucket does not expose objects publicly.
- [ ]  Upload slot endpoint returns a temporary upload URL.
- [ ]  Confirm endpoint verifies the uploaded object.
- [ ]  Private download endpoint checks access before URL creation.
- [ ]  Media metadata is stored in the database.
- [ ]  Delete/discard flow updates media status.
- [ ]  Beauty product mapping data exists.
- [ ]  Recommendation service returns scored products.
- [ ]  Recommendation card includes reasons and warnings.
- [ ]  Storefront renders recommendation output.
- [ ]  Vendor/admin mapping support exists or a controlled seed process is documented.
- [ ]  Browser bundle/network contains no backend storage/provider secrets.
- [ ]  Root and service docs are updated; changelog is updated.

# 10. Next phase after PoC

Seller consultation sessions; customer beauty profiles; profile snapshots; Perfect Corp / YouCam task pipeline; demo-mode fallback; quota accounts and quota events; failed-task admin view; saved-profile reopen flow. **Do not** move into full customer self-scan or multi-provider AI before storage, mappings, recommendations, and media security are verified.

# 11. Related documentation

- Canonical: [PoC 12 — Storage & Beauty Intelligence Foundation (Phase 4)](https://app.notion.com/p/PoC-12-Storage-Beauty-Intelligence-Foundation-Phase-4-0b6ab14688844db88c69148cff827930?pvs=21). RFC: [[rfc-phase-4-storage-beauty-intelligence.md](http://rfc-phase-4-storage-beauty-intelligence.md)](../rfc/rfc-phase-4-storage-beauty-intelligence%20md%207b0d9680123e464e96484dcd92100966.md). Storage: `storage/storage-architecture.md`, `storage/private-bucket-policy.md`. Backend: `backend-engine/api-contracts.md`, `backend-engine/database-schema.md`. Decisions: [ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21).