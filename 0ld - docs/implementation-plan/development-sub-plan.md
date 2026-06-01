# Development Sub-Plan

## 1. Objectives

Define the engineering execution plan for Phase 4: S3-compatible storage foundation and beauty product intelligence.

Primary objectives:

```text
Implement backend storage foundation in Laravel.
Add media metadata and upload/download APIs.
Add beauty product mapping data model.
Add deterministic recommendation scoring.
Integrate storefront recommendation UI.
Add vendor/admin support where practical.
Keep implementation aligned with current repo structure.
```

## 2. Scope

In scope:

```text
backend-engine Laravel code
MySQL migrations
S3-compatible storage config
storefront recommendation UI
admin/vendor mapping support
service docs and API docs
```

Out of scope:

```text
Prisma/Postgres
apps/web Next.js monolith
Next.js API routes for backend logic
live AI provider integration
mobile app
subscription billing
```

## 3. Tasks and Activities

### 3.1 Backend Storage

```text
Add S3-compatible env variables.
Add Laravel disks for public/private buckets.
Create storage service layer.
Create media metadata migration/model.
Create upload-slot API.
Create confirm-upload API.
Create signed-download API.
Create delete/discard flow.
```

### 3.2 Beauty Intelligence

```text
Create beauty_product_mappings migration/model.
Create beauty_recommendations migration/model.
Create feature extraction service.
Create weighted scoring service.
Create explanation service.
Create recommendation API endpoint.
Seed mapped products for validation.
```

### 3.3 Frontend Integration

```text
Create recommendation card component in storefront.
Render score, confidence, reasons, and warnings.
Add vendor/admin mapping UI or document controlled seed workflow.
Ensure API calls use backend REST endpoint.
```

## 4. Owners / Responsibilities

| Owner | Responsibilities |
|---|---|
| Backend Engineer | Laravel APIs, migrations, services, storage, scoring |
| Frontend Engineer | Storefront/vendor/admin UI integration |
| DevOps | Storage endpoint, bucket policies, deployment variables |
| Product Owner | Scope, copy, recommendation output review |
| Documentation Owner | Docs, changelog, implementation notes |

## 5. Timelines

| Work item | Estimate |
|---|---:|
| Storage config | 1-2 days |
| Media APIs | 2-3 days |
| Beauty mappings | 2 days |
| Recommendation engine | 2-3 days |
| Frontend integration | 2 days |
| Cleanup/docs | 1 day |

## 6. Tools and Technologies

```text
Laravel 13
PHP 8.3
MySQL
Redis
Laravel filesystem / Flysystem S3 adapter
MinIO / AIStor-compatible S3 endpoint
Next.js 15
React 19
Yarn Classic
Docker Compose
```

## 7. Risks and Dependencies

| Risk/Dependency | Impact | Response |
|---|---|---|
| Storage endpoint not configured | Blocks media APIs | Validate endpoint before API build |
| Existing commerce package route conflicts | Medium | Inspect route list before adding routes |
| Frontend API contract drift | Medium | Document request/response shapes first |
| Scope creep into AI | High | Keep provider integration outside Phase 4 |

## 8. Deliverables

```text
Laravel storage config
media metadata migration
storage API endpoints
beauty mapping migration
recommendation service
storefront recommendation UI
docs and changelog updates
```

## 9. Approval Criteria

```text
All new routes documented.
Migrations run successfully.
Upload and signed-read flows verified.
Recommendation endpoint returns scored results.
Storefront renders explanations.
No frontend secrets exposed.
Code follows actual repo structure.
```
