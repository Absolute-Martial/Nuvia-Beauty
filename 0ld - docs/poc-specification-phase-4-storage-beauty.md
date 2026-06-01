# Proof of Concept Specification: Phase 4 Storage Foundation and Beauty Product Intelligence

## Document Status

| Field | Value |
|---|---|
| Project | Nuvia Beauty |
| Repository | `Absolute-Martial/Nuvia-Beauty` |
| Branch | `development` |
| PoC phase | Phase 4: S3-compatible storage foundation and beauty product intelligence |
| Current deployment state | Base app deployed for testing |
| Primary services | `backend-engine`, `storefront`, `admin-panel`, `vendor-portal` |
| Storage direction | S3-compatible object storage, MinIO/AIStor preferred self-hosted provider |

## 1. Executive Summary

Nuvia Beauty is being advanced from a deployed base commerce application into a beauty-focused commerce and consultation platform. The current base deployment already exposes the core runtime surfaces: customer storefront, admin panel, vendor portal, backend API, and S3/MinIO-compatible endpoints. The next PoC focuses on turning this deployed base into a structured, push-ready product foundation by implementing S3-compatible media storage and the first beauty product intelligence layer.

This PoC will prove that Nuvia Beauty can safely manage public and private media, support beauty-specific product metadata, generate deterministic product recommendations, and prepare the platform for future AI/provider workflows such as skin analysis, virtual try-on, seller-assisted consultation, and reusable customer beauty profiles.

The immediate priority is not full AI automation. The priority is the foundation required before AI can be trusted: storage boundaries, media ownership, product mappings, recommendation rules, admin/vendor controls, and clear documentation.

## 2. Current Deployment Context

The base application is already deployed in a test/staging environment.

Observed deployed service surfaces:

| Surface | Purpose | Port |
|---|---|---:|
| Storefront | Customer-facing shop | `3003` |
| Admin Panel | Platform/admin dashboard | `3002` |
| Vendor Portal | Seller/vendor dashboard | `3004` |
| Backend API | Laravel backend API | `8000` |
| S3 Assets Endpoint | S3-compatible object API endpoint | `9000` |
| S3 Console Endpoint | Storage console/admin UI | `9001` |

The deployed environment uses HTTPS, Let's Encrypt certificates, and Cloudflare-backed domains. This provides a valid foundation for browser-based workflows, media uploads, PWA testing, and API integration.

## 3. Objectives and Goals

### 3.1 Primary Objectives

1. Implement an S3-compatible storage architecture that works with MinIO/AIStor and remains portable to AWS S3 or another compatible provider.
2. Separate public media from private beauty/customer media.
3. Create backend-controlled media upload and access flows.
4. Add beauty-specific product metadata and mapping capabilities.
5. Implement deterministic recommendation scoring with reason and warning cards.
6. Prepare the backend for future AI/provider task orchestration without exposing secrets or private media directly.
7. Keep all documentation accurate, current, and separated by service.

### 3.2 Product Goals

The PoC should demonstrate that Nuvia Beauty is more than a generic ecommerce deployment.

It must show:

```text
Customers receive clearer beauty product guidance.
Vendors can configure beauty-relevant product data.
Admins can control mapping, storage, and operational settings.
The backend can safely handle public/private media.
The system is ready for future AI analysis and try-on workflows.
```

### 3.3 Engineering Goals

```text
No frontend storage credentials.
No hardcoded MinIO-only logic in business code.
No raw private media stored in MySQL.
No undocumented route or data-flow changes.
No planned features described as already implemented.
```

## 4. Scope and Limitations

### 4.1 In Scope

#### Storage Foundation

- S3-compatible backend configuration.
- MinIO/AIStor-compatible environment variables.
- Public bucket for product/shop/public assets.
- Private bucket for beauty input media.
- Private bucket for beauty result media.
- Optional private calibration bucket design.
- Media metadata table.
- Backend-issued upload slot endpoint.
- Upload confirmation endpoint.
- Backend-authorized private download URL endpoint.
- Media delete/discard flow.
- Basic retention/expiry job structure.

#### Beauty Product Intelligence

- Beauty product attributes.
- Product concern tags.
- Skin type tags.
- Skin tone and undertone tags.
- Ingredient and avoid tags.
- Product mapping table or equivalent model.
- Deterministic recommendation scoring.
- Reason cards.
- Warning cards.
- Admin/vendor-visible mapping support where practical.

#### Documentation

- Root PoC documentation.
- Service-level documentation updates.
- Storage implementation checklist.
- Current vs planned labels.
- API contract documentation for new endpoints.

### 4.2 Out of Scope for This PoC

The following are intentionally deferred:

```text
Full customer self-scan
Full Perfect Corp / YouCam live workflow
Makeup virtual try-on as a required feature
Hair analysis or hair try-on
Jewelry/accessory try-on
Native mobile app
Subscription billing
Advanced analytics dashboard
Cross-profile ML recommendation engine
Clinical or medical diagnosis wording
Social sharing of beauty results
```

### 4.3 Constraints

- Existing service layout must be preserved: `backend-engine`, `storefront`, `admin-panel`, `vendor-portal`.
- Laravel remains backend authority for protected operations.
- Frontend apps remain browser-facing clients only.
- Storage implementation must stay S3-compatible, not MinIO-hardcoded.
- Private customer/beauty media must not become public assets.
- AI/provider keys must remain backend-only.

## 5. Technical Requirements

### 5.1 Backend Requirements

Backend service: `backend-engine`

Required additions:

```text
S3-compatible disk configuration
media metadata migration
storage service layer
upload-slot API endpoint
upload-confirm API endpoint
private-download API endpoint
media delete/discard job
beauty product mapping model/table
recommendation scoring service
reason/warning response builder
admin/vendor-safe API responses
```

Recommended backend structure:

```text
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

### 5.2 Storage Requirements

Required storage model:

| Bucket | Visibility | Purpose |
|---|---|---|
| `nuvia-public-assets` | Public/CDN-readable | Product images, shop logos, banners, public UI assets |
| `nuvia-private-beauty-inputs` | Private | Customer/source photos or future scan inputs |
| `nuvia-private-beauty-results` | Private | Generated result media, overlays, future try-on outputs |
| `nuvia-private-calibration` | Private | Explicit opt-in calibration images, later phase |

Required environment pattern:

```env
STORAGE_DRIVER=s3_compatible
S3_PROVIDER=minio_aistor
S3_ENDPOINT=https://s3-assets-example-domain
S3_REGION=us-east-1
S3_ACCESS_KEY_ID=
S3_SECRET_ACCESS_KEY=
S3_USE_PATH_STYLE_ENDPOINT=true

S3_PUBLIC_BUCKET=nuvia-public-assets
S3_BEAUTY_INPUTS_BUCKET=nuvia-private-beauty-inputs
S3_BEAUTY_RESULTS_BUCKET=nuvia-private-beauty-results
S3_BEAUTY_CALIBRATION_BUCKET=nuvia-private-calibration

S3_UPLOAD_URL_TTL_MINUTES=15
S3_DOWNLOAD_URL_TTL_MINUTES=60
```

Required upload flow:

```text
Frontend requests upload slot
Backend validates actor, purpose, MIME type, size, and context
Backend creates pending media metadata
Backend returns short-lived upload URL
Frontend uploads object directly to S3-compatible endpoint
Frontend confirms upload with backend
Backend verifies object and marks media confirmed
```

Required private read flow:

```text
Frontend requests media access
Backend validates ownership/permission
Backend returns short-lived signed download URL
Frontend uses temporary URL
```

### 5.3 Database Requirements

Minimum new tables or equivalent models:

```text
media_assets or beauty_media_assets
beauty_product_mappings
beauty_recommendations
```

Recommended `beauty_media_assets` fields:

```text
id
session_id nullable
profile_id nullable
shop_id nullable
owner_type
owner_id
asset_type
storage_provider
disk_name
bucket
object_key
object_version nullable
content_type
size_bytes
checksum_sha256 nullable
visibility
status
expires_at nullable
discarded_at nullable
deleted_at nullable
created_at
updated_at
```

Recommended `beauty_product_mappings` fields:

```text
id
product_id
concern_tags JSON
tone_tags JSON
skin_type_tags JSON
ingredient_tags JSON
avoid_tags JSON
explanation_template nullable
created_at
updated_at
```

Recommended `beauty_recommendations` fields:

```text
id
customer_id nullable
profile_id nullable
session_id nullable
product_id
score
confidence
reasons_json
warnings_json
accepted nullable
dismissed nullable
created_at
updated_at
```

### 5.4 Frontend Requirements

#### Storefront

Required:

```text
render beauty product attributes
render recommendation cards
show reason/warning cards
consume backend recommendation endpoint
prepare upload flow integration if media upload is exposed in PoC
```

#### Vendor Portal

Required:

```text
allow vendor to view/edit beauty product metadata where practical
support product image/media flow through backend-controlled storage
show product mapping fields or mapping status
```

#### Admin Panel

Required:

```text
admin product mapping controls where practical
storage/provider configuration visibility where practical
failed media/provider task placeholders if full workflow is deferred
```

### 5.5 API Requirements

Planned API endpoints:

```http
POST /api/v1/storage/upload-slots
POST /api/v1/storage/media/{mediaId}/confirm
GET  /api/v1/storage/media/{mediaId}/download-url
DELETE /api/v1/storage/media/{mediaId}
GET  /api/v1/beauty/recommendations
POST /api/v1/beauty/recommendations/generate
GET  /api/v1/beauty/product-mappings
POST /api/v1/beauty/product-mappings
PUT  /api/v1/beauty/product-mappings/{id}
```

If existing package route conventions conflict, use the closest existing Laravel route style but document the final API contract.

### 5.6 Security and Access Requirements

```text
Frontend must not receive storage access keys.
Frontend must not receive provider API keys.
Private media must not be stored in public buckets.
Signed URLs must be short-lived.
Backend must validate ownership before private media access.
Backend must validate file type and size before upload slot creation.
Backend must avoid logging full signed URLs or secrets.
```

## 6. Success Criteria and KPIs

### 6.1 Technical Success Criteria

| Criterion | Target |
|---|---|
| Backend starts successfully | Required |
| Storefront starts successfully | Required |
| Admin panel starts successfully | Required |
| Vendor portal starts successfully | Required |
| S3-compatible storage config works | Required |
| Public media bucket works | Required |
| Private media bucket blocks public access | Required |
| Upload slot endpoint works | Required |
| Upload confirmation works | Required |
| Private signed download works | Required |
| Product mapping data persists | Required |
| Recommendation endpoint returns scored products | Required |
| Reason/warning cards render | Required |
| No storage/provider secrets in browser | Required |
| Documentation updated | Required |

### 6.2 Product Success Criteria

| Criterion | Target |
|---|---|
| Beauty product attributes visible in product workflow | Yes |
| At least 10 seeded beauty products with mapping data | Minimum |
| Recommendation card returns top 3 products | Minimum |
| Each recommendation includes reasons | Minimum 2 reasons |
| Warnings are shown when avoid tags match | Required |
| Vendor/admin can support mapping workflow | Basic capability or documented controlled seed workflow |

### 6.3 KPI Targets

| KPI | PoC target |
|---|---:|
| Upload slot creation latency | Under 500 ms excluding network variance |
| Signed upload TTL | 15 minutes default |
| Signed download TTL | 60 minutes default |
| Recommendation generation latency | Under 1 second for seeded dataset |
| Public/private storage separation | 100% pass in manual verification |
| Secret exposure in browser bundle/network | 0 findings |
| Seed product coverage | At least 10 beauty products |
| Recommendation output coverage | At least 3 products per eligible request |
| Documentation coverage | Root + all four services updated |

## 7. Timeline and Milestones

### Milestone 1: Baseline Verification

Duration: 0.5 to 1 day

Deliverables:

```text
Confirm deployed storefront, admin, vendor, backend, and S3 endpoints
Confirm Docker Compose and deployed service ports
Confirm current docs match deployed state
Update changelog with PoC start
```

Exit criteria:

```text
All current services reachable
Known environment values documented
No confusion between current and planned features
```

### Milestone 2: Storage Configuration

Duration: 1 to 2 days

Deliverables:

```text
S3-compatible env variables
Laravel public/private disks
MinIO/AIStor endpoint support
Bucket naming convention
Storage docs updated
```

Exit criteria:

```text
Backend can connect to S3-compatible endpoint
Public and private disks are separated
No frontend credentials are introduced
```

### Milestone 3: Media Upload Flow

Duration: 2 to 3 days

Deliverables:

```text
media metadata migration
storage service layer
upload slot endpoint
confirm endpoint
private download endpoint
delete/discard flow
```

Exit criteria:

```text
Public upload works
Private upload works
Private object is not public
Signed download works after backend authorization
```

### Milestone 4: Beauty Product Mapping

Duration: 2 to 3 days

Deliverables:

```text
beauty product mapping table/model
seed mapping data
admin or vendor mapping workflow where practical
beauty attributes in product payloads
```

Exit criteria:

```text
At least 10 products have beauty mapping data
Mappings can be read by backend recommendation service
```

### Milestone 5: Recommendation Engine

Duration: 2 to 3 days

Deliverables:

```text
recommendation scoring service
reason card builder
warning card builder
recommendation endpoint
storefront rendering
```

Exit criteria:

```text
Customer-facing product recommendation card shows top products, scores, reasons, and warnings
```

### Milestone 6: Verification and Push Readiness

Duration: 1 to 2 days

Deliverables:

```text
manual QA checklist
secret exposure check
storage access check
seed data verification
docs/changelog update
push-ready summary
```

Exit criteria:

```text
PoC can be demonstrated from deployed environment
Docs match actual state
Known gaps are explicitly listed
```

## 8. Resource Requirements

### 8.1 Engineering Roles

| Role | Responsibility |
|---|---|
| Backend engineer | Laravel storage, media metadata, APIs, recommendation service |
| Frontend engineer | Storefront/vendor/admin UI integration |
| DevOps engineer | S3/MinIO/AIStor endpoint, bucket setup, deployment validation |
| QA/tester | Manual verification, storage access tests, UI flow checks |
| Product owner | Scope control, acceptance criteria, copy/language review |

For a solo build, execute in this order:

```text
backend storage
backend mappings
recommendation endpoint
storefront display
vendor/admin support
QA/docs
```

### 8.2 Infrastructure Requirements

```text
Existing deployed app environment
Backend API service
MySQL database
Redis service
S3-compatible object storage endpoint
Public and private buckets
Cloudflare/HTTPS routing
Laravel logs
Browser DevTools verification
```

### 8.3 Data Requirements

```text
At least 10 beauty products
Product categories
Product images
Concern tags
Skin type tags
Tone/undertone tags
Avoid tags
Recommendation seed profiles or request payloads
```

### 8.4 Documentation Requirements

```text
Root PoC spec
Root storage docs
Backend storage docs
Backend API routes docs
Frontend service docs updated where integration changes
Changelog entry
Implementation checklist
```

## 9. Risk Assessment

| Risk | Impact | Probability | Mitigation |
|---|---|---:|---|
| S3-compatible endpoint misconfiguration | Uploads fail | Medium | Use env-driven config and verify disks separately |
| Private media accidentally public | High privacy risk | Medium | Separate buckets and test public access denial |
| Frontend receives storage credentials | High security risk | Low/Medium | Backend-only env and browser network check |
| MinIO-specific code blocks future S3 portability | Medium | Medium | Code against Laravel/Flysystem abstraction |
| Recommendation logic feels weak | Medium product risk | Medium | Use clear reason cards and seeded beauty tags |
| Scope expands into full AI before foundation | High delivery risk | High | Keep AI/provider live calls out of required PoC scope |
| Existing template routes conflict with new API style | Medium | Medium | Document final route contracts and adapt cleanly |
| Documentation drifts from implementation | Medium | Medium | Update docs in same commit as behavior changes |
| Deployment routing mismatch | Medium | Medium | Verify each deployed domain and backend endpoint |
| Large files or invalid MIME uploads | Medium | Medium | Backend validation before upload slot creation |

## 10. Expected Outcomes

### 10.1 Technical Outcomes

By the end of the PoC, Nuvia Beauty should have:

```text
S3-compatible storage foundation
public/private media separation
backend-issued upload URLs
private signed read flow
media metadata tracking
beauty product mapping data
recommendation scoring service
customer-facing recommendation display
updated documentation
clear next-phase implementation path
```

### 10.2 Product Outcomes

The system should demonstrate:

```text
Nuvia Beauty is a beauty-commerce platform, not only a generic shop template.
Products can carry beauty-specific intelligence.
Customers can receive explainable product suggestions.
Vendors/admins can support beauty metadata workflows.
The platform is ready for future AI analysis and virtual try-on.
```

### 10.3 Business Outcomes

The PoC should support the next push by proving:

```text
The base deployment is operational.
The storage foundation is safe and scalable.
The beauty recommendation layer is visible and explainable.
The system can be demoed to shops, collaborators, or early testers.
The future AI roadmap has a stable technical base.
```

## 11. Acceptance Checklist

```text
[ ] Backend runs with configured storage env.
[ ] Public bucket can serve product/shop assets.
[ ] Private bucket does not expose objects publicly.
[ ] Upload slot endpoint returns temporary upload URL.
[ ] Confirm endpoint verifies uploaded object.
[ ] Private download endpoint checks access before URL creation.
[ ] Media metadata is stored in database.
[ ] Delete/discard flow updates media status.
[ ] Beauty product mapping data exists.
[ ] Recommendation service returns scored products.
[ ] Recommendation card includes reasons and warnings.
[ ] Storefront renders recommendation output.
[ ] Vendor/admin mapping support exists or controlled seed process is documented.
[ ] Browser bundle/network contains no backend storage/provider secrets.
[ ] Root and service docs are updated.
[ ] Changelog is updated.
```

## 12. Next Phase After PoC

After this PoC succeeds, proceed to seller-assisted consultation and provider orchestration.

Next phase candidates:

```text
seller consultation sessions
customer beauty profiles
profile snapshots
Perfect Corp / YouCam task pipeline
demo mode fallback
quota accounts and quota events
failed task admin view
saved profile reopen flow
```

Do not move into full customer self-scan or multi-provider AI before storage, mappings, recommendations, and media security are verified.
