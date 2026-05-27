# Implementation Plan: Phase 4 Storage Foundation and Beauty Product Intelligence

## Document Control

| Field | Value |
|---|---|
| Project | Nuvia Beauty |
| Repository | `Absolute-Martial/Nuvia-Beauty` |
| Branch | `development` |
| Document type | Implementation Plan Package |
| Status | Draft for execution alignment |
| Target phase | Phase 4 |
| Primary scope | S3-compatible storage foundation and beauty product intelligence |
| Audience | Product, engineering, architecture, deployment, QA, and stakeholder teams |

## Sub-Plan Documents

| Sub-plan | Path |
|---|---|
| Development Sub-Plan | `docs/implementation-plan/development-sub-plan.md` |
| Testing Sub-Plan | `docs/implementation-plan/testing-sub-plan.md` |
| Deployment Sub-Plan | `docs/implementation-plan/deployment-sub-plan.md` |
| Security Sub-Plan | `docs/implementation-plan/security-sub-plan.md` |
| Infrastructure Sub-Plan | `docs/implementation-plan/infrastructure-sub-plan.md` |
| Rollback and Recovery Plan | `docs/implementation-plan/rollback-recovery-plan.md` |
| Monitoring and Maintenance Plan | `docs/implementation-plan/monitoring-maintenance-plan.md` |
| Documentation Plan | `docs/implementation-plan/documentation-plan.md` |

## 1. Executive Summary

Nuvia Beauty has a deployed base application with four core services: `backend-engine`, `storefront`, `admin-panel`, and `vendor-portal`. The next implementation phase will convert the deployed base into a differentiated beauty-commerce foundation by adding S3-compatible media storage and the first beauty product intelligence layer.

This implementation plan defines the work required to build, validate, deploy, and maintain the Phase 4 scope. The plan intentionally prioritizes infrastructure-safe foundations before advanced AI workflows. The immediate target is to prove secure media handling, public/private storage separation, beauty product mapping, deterministic recommendation scoring, and explainable product recommendations.

## 2. Project Objectives

### 2.1 Business Objectives

```text
Position Nuvia Beauty as a beauty-commerce platform, not a generic ecommerce template.
Enable vendors to manage beauty-relevant product information.
Help customers understand why products are recommended.
Prepare the system for seller consultation and future AI/provider workflows.
Create a push-ready implementation path with clear acceptance criteria.
```

### 2.2 Technical Objectives

```text
Implement S3-compatible storage with MinIO/AIStor as preferred self-hosted provider.
Separate public and private media buckets.
Add backend-controlled upload, confirmation, and signed read flows.
Persist media metadata in MySQL through Laravel migrations.
Add beauty product mappings and recommendation records.
Implement deterministic recommendation scoring with explainable breakdown.
Expose safe APIs for the three frontend services.
Update root and service documentation during implementation.
```

## 3. Scope and Deliverables

### 3.1 In Scope

| Area | Deliverables |
|---|---|
| Storage | S3-compatible env variables, Laravel disks, public/private bucket design, media metadata table, upload-slot API, confirm API, signed download API, delete/discard flow |
| Beauty intelligence | Product mapping model, concern/skin/tone/ingredient/avoid tags, scoring service, score versioning, recommendation records, reason/warning builders |
| Frontend integration | Storefront recommendation cards, vendor/admin mapping support where practical, safe media flow integration points |
| Backend services | Laravel controllers, services, policies, jobs, migrations, route documentation |
| Infrastructure | MinIO/AIStor endpoint validation, bucket policy validation, Docker/deployment environment alignment |
| Documentation | Implementation plan package, API docs, storage docs, changelog updates |

### 3.2 Out of Scope

```text
Full live AI provider workflow.
Full customer self-scan.
Mobile app implementation.
Subscription billing.
Advanced analytics platform.
Multi-provider orchestration.
Clinical or medical diagnosis features.
Full marketplace payout/commission rebuild unless existing base requires fixes.
```

## 4. Assumptions and Constraints

### 4.1 Assumptions

```text
The deployed base app remains available for validation.
The current service layout remains unchanged.
Laravel remains the backend authority.
MySQL remains the relational database.
Redis remains available for cache, session, and future queues.
S3-compatible object storage is available through MinIO/AIStor or compatible endpoint.
Existing commerce package/data model can remain the commerce foundation.
```

### 4.2 Constraints

```text
Do not create `apps/web` or Prisma/Postgres implementation paths.
Do not create backend business logic inside Next.js API routes.
Do not expose storage or provider credentials to frontend services.
Do not store raw media bytes in MySQL.
Do not make private beauty/customer media public.
Do not document planned features as current implementation.
```

## 5. Architecture / Solution Overview

### 5.1 Service Architecture

```text
storefront      -> backend-engine -> MySQL
admin-panel     -> backend-engine -> Redis
vendor-portal   -> backend-engine -> S3-compatible object storage
backend-engine  -> future external provider APIs
```

### 5.2 Target Backend Domains

```text
backend-engine/app/Domains/Storage/
backend-engine/app/Domains/Beauty/
```

Storage domain responsibilities:

```text
bucket selection
object key generation
upload slot creation
upload confirmation
private signed URL creation
media metadata lifecycle
expiry/delete/discard jobs
```

Beauty domain responsibilities:

```text
beauty product mappings
profile/recommendation criteria handling
deterministic scoring
reason and warning generation
recommendation persistence
event/signal collection
```

## 6. Implementation Strategy

Implementation will proceed in dependency order:

```text
1. Repository verification and route inventory.
2. Storage environment and Laravel disk configuration.
3. Media metadata and backend storage APIs.
4. Beauty product mapping data model.
5. Recommendation scoring service.
6. Storefront recommendation rendering.
7. Vendor/admin mapping support.
8. Event/signal foundation.
9. QA, documentation, deployment verification, and push-readiness review.
```

This order prevents UI and AI work from being built on unsafe or incomplete media foundations.

## 7. Work Breakdown Structure (WBS)

| WBS ID | Workstream | Deliverables | Dependency |
|---|---|---|---|
| 1.0 | Repo and baseline verification | Current route list, deployed endpoint list, environment inventory | None |
| 2.0 | Storage configuration | Env vars, Laravel disks, bucket names, docs | 1.0 |
| 3.0 | Media metadata | Migration, model, statuses, ownership fields | 2.0 |
| 4.0 | Storage APIs | Upload slot, confirm, signed read, delete/discard | 3.0 |
| 5.0 | Beauty mappings | Migration, model, APIs, seed data | 1.0 |
| 6.0 | Recommendation engine | Feature extraction, scoring, reasons, warnings, versioning | 5.0 |
| 7.0 | Frontend integration | Storefront cards, vendor/admin mapping UI | 4.0, 6.0 |
| 8.0 | Event/signal layer | Event endpoint, product signal model, recompute job/command | 6.0 |
| 9.0 | Testing and validation | API tests, manual QA, security checks | 4.0, 7.0 |
| 10.0 | Deployment and rollout | Staging validation, env checks, release notes | 9.0 |
| 11.0 | Documentation | Updated root/service docs, changelog | Continuous |

## 8. Phases and Milestones

### Phase 0: Planning and Baseline Confirmation

Deliverables:

```text
Confirm actual repo structure.
Confirm deployed service URLs and ports.
Run or document route inventory.
Confirm current docs align with implementation state.
```

Exit criteria:

```text
No ambiguity about implementation paths.
No accidental Prisma/Postgres/apps-web plan remains.
```

### Phase 1: Storage Foundation

Deliverables:

```text
S3-compatible env variables.
Laravel disks for public/private buckets.
Bucket policy and CORS requirements.
Storage service interface.
```

Exit criteria:

```text
Backend can connect to S3-compatible endpoint.
Public/private storage separation is configured.
```

### Phase 2: Media APIs

Deliverables:

```text
media metadata migration.
upload-slot endpoint.
confirm endpoint.
signed download endpoint.
delete/discard flow.
```

Exit criteria:

```text
Private media upload/read flow works without frontend credentials.
```

### Phase 3: Beauty Product Mapping

Deliverables:

```text
beauty_product_mappings table.
model and service layer.
seed data for at least 10 beauty products.
admin/vendor mapping support or controlled seed workflow.
```

Exit criteria:

```text
Products have queryable beauty intelligence metadata.
```

### Phase 4: Recommendation Engine

Deliverables:

```text
feature extraction.
weighted scoring.
reason cards.
warning cards.
recommendation endpoint.
recommendation persistence where needed.
```

Exit criteria:

```text
Storefront can display explainable recommendations.
```

### Phase 5: Integration and Push Readiness

Deliverables:

```text
frontend integration.
manual QA.
security validation.
deployment verification.
docs and changelog updates.
```

Exit criteria:

```text
Phase 4 demo is push-ready.
```

## 9. Timeline and Schedule

Indicative schedule for solo or small-team execution:

| Phase | Duration | Calendar estimate |
|---|---:|---|
| Phase 0 | 0.5-1 day | Day 1 |
| Phase 1 | 1-2 days | Days 1-3 |
| Phase 2 | 2-3 days | Days 3-6 |
| Phase 3 | 2-3 days | Days 6-9 |
| Phase 4 | 2-3 days | Days 9-12 |
| Phase 5 | 2 days | Days 12-14 |

Schedule risk buffer:

```text
Add 20-30% buffer for deployment, storage policy, and existing codebase integration issues.
```

## 10. Resource Allocation

| Role | Allocation | Responsibility |
|---|---:|---|
| Product Owner | Part-time | Scope, acceptance, product value validation |
| Backend Engineer | Primary | Laravel APIs, migrations, storage, recommendations |
| Frontend Engineer | Primary/part-time | Storefront/vendor/admin integration |
| DevOps/Infrastructure | Part-time | S3/MinIO/AIStor, deployment, env validation |
| QA/Test Owner | Part-time | Test cases, regression, security validation |
| Documentation Owner | Shared | Docs, changelog, implementation records |

Solo execution order:

```text
backend storage -> media APIs -> beauty mappings -> scoring -> storefront UI -> admin/vendor support -> validation/docs
```

## 11. Roles and Responsibilities

| Area | Owner | Reviewer |
|---|---|---|
| Architecture | Lead Architect | Product Owner |
| Storage | Backend/DevOps | Security Reviewer |
| Database | Backend Engineer | Lead Architect |
| Recommendation Logic | Backend Engineer | Product Owner |
| Storefront UI | Frontend Engineer | Product Owner |
| Vendor/Admin UI | Frontend Engineer | Product Owner |
| Deployment | DevOps | Backend Engineer |
| Security Review | Security Reviewer | Lead Architect |
| QA | QA/Test Owner | Product Owner |
| Documentation | Documentation Owner | Engineering Lead |

## 12. Environment and Infrastructure Requirements

Required services:

```text
Laravel backend service.
MySQL database.
Redis service.
S3-compatible storage endpoint.
Public assets bucket.
Private beauty inputs bucket.
Private beauty results bucket.
HTTPS routing for deployed services.
Docker Compose or equivalent deployment orchestration.
```

Required environments:

```text
Local development.
Staging/test deployment.
Production-ready path after validation.
```

## 13. Development Plan

Development must follow the dedicated Development Sub-Plan.

Core rules:

```text
Use Laravel migrations for MySQL schema changes.
Use Laravel services/controllers/jobs for backend logic.
Use existing Next.js app directories for frontend code.
Do not create new monolithic app paths.
Document all new routes and environment variables.
```

## 14. Integration Plan

Integration sequence:

```text
Backend storage service -> storage APIs -> frontend upload client.
Beauty mappings -> recommendation service -> storefront recommendation UI.
Event endpoint -> product signal model -> future recompute flow.
Admin/vendor screens -> backend mapping APIs.
```

Integration points:

```text
storefront <-> backend-engine
admin-panel <-> backend-engine
vendor-portal <-> backend-engine
backend-engine <-> MySQL
backend-engine <-> Redis
backend-engine <-> S3-compatible storage
```

## 15. Testing and Validation Plan

Testing must follow the Testing Sub-Plan.

Minimum validation gates:

```text
Laravel config loads.
Migrations run.
Route list includes new APIs.
Upload-slot flow works.
Private bucket blocks public read.
Signed download works after authorization.
Recommendation endpoint returns scored products.
Storefront renders reason/warning cards.
No secrets are visible in browser network or bundle.
```

## 16. Deployment and Rollout Plan

Deployment must follow the Deployment Sub-Plan.

Rollout pattern:

```text
Deploy backend config and migrations.
Validate storage endpoints and bucket access.
Deploy backend APIs.
Deploy frontend recommendation UI behind safe fallback.
Run staging smoke tests.
Promote to production when acceptance checklist passes.
```

## 17. Security and Compliance Considerations

Security must follow the Security Sub-Plan.

Key requirements:

```text
Backend-only credentials.
Short-lived signed URLs.
Ownership checks before private media access.
Private media buckets are not public.
No clinical/medical claims.
No raw private media bytes in MySQL.
No sensitive data in logs.
```

## 18. Risk Assessment and Mitigation Plan

| Risk | Impact | Probability | Mitigation |
|---|---|---:|---|
| Storage misconfiguration | Upload/read failures | Medium | Validate each disk and bucket separately |
| Private media exposure | High | Medium | Public/private bucket tests and policy review |
| Incorrect repo implementation path | High | Medium | Enforce repo constraints and docs before coding |
| Recommendation value weak | Medium | Medium | Use curated mappings, reasons, and warnings |
| Scope creep into AI | High | High | Keep AI/provider flow out of Phase 4 acceptance |
| Deployment env drift | Medium | Medium | Maintain env matrix and deployment checklist |
| Docs drift | Medium | Medium | Update docs in same commit as behavior changes |

## 19. Monitoring and Support Plan

Monitoring must follow the Monitoring and Maintenance Sub-Plan.

Minimum support checks:

```text
backend API health
storage endpoint health
upload failure rate
media delete failure rate
recommendation endpoint latency
frontend error reports
server logs for storage/provider failures
```

## 20. Communication Plan

| Audience | Frequency | Channel/Artifact |
|---|---|---|
| Product/Stakeholders | Milestone completion | Summary note + screenshots/demo |
| Engineering | Per implementation phase | Commit notes, docs, issue/PR discussion |
| Deployment owner | Before deploy and after deploy | Deployment checklist |
| QA | Before validation | Test plan and acceptance checklist |
| Security reviewer | Before release | Security checklist |

## 21. Change Management Process

Change categories:

```text
Minor: docs, copy, non-behavioral UI changes.
Standard: API, UI, model, or config changes within approved scope.
Major: architecture, storage provider, auth, database ownership, deployment topology changes.
```

Major changes require:

```text
RFC update.
HLD update if architecture changes.
Implementation plan update.
Stakeholder approval.
Rollback plan review.
```

## 22. Dependencies

| Dependency | Type | Owner |
|---|---|---|
| Laravel backend | Internal | Backend Engineer |
| MySQL | Infrastructure | DevOps/Backend |
| Redis | Infrastructure | DevOps/Backend |
| S3-compatible storage | Infrastructure | DevOps |
| MinIO/AIStor | Preferred provider | DevOps |
| Storefront | Frontend | Frontend Engineer |
| Admin Panel | Frontend | Frontend Engineer |
| Vendor Portal | Frontend | Frontend Engineer |
| Cloudflare/HTTPS routing | Infrastructure | DevOps |
| Future Perfect Corp/YouCam API | External | Product/Backend |

## 23. Success Criteria / KPIs

| KPI | Target |
|---|---:|
| Upload-slot API | Functional end-to-end |
| Private object public access | 0 successful unauthorized reads |
| Signed URL TTL | 15 min upload, 60 min download defaults |
| Recommendation generation | Under 1 second for seeded dataset |
| Seeded mapped products | Minimum 10 |
| Recommendation output | Minimum 3 products per eligible request |
| Recommendation explanation | Minimum 2 reasons per product |
| Frontend secret exposure | 0 findings |
| Documentation coverage | Root and affected services updated |
| Deployment smoke tests | 100% pass before release |

## 24. Budget or Cost Considerations

Expected cost areas:

```text
server compute for backend/frontends
MySQL and Redis resource usage
S3-compatible storage disk usage
Cloudflare/domain/HTTPS routing
future provider API credits
future monitoring/error tracking service
```

Cost controls:

```text
Use self-hosted MinIO/AIStor-compatible storage where appropriate.
Set media retention policies.
Avoid live AI calls in Phase 4.
Use deterministic scoring before paid provider workflows.
Track future quota usage before enabling provider calls.
```

## 25. Post-Implementation Review

Review should occur after Phase 5 acceptance.

Review agenda:

```text
Compare delivered scope against acceptance checklist.
Review storage security validation results.
Review recommendation output quality.
Review deployment incidents or blockers.
Review docs accuracy.
Identify next phase readiness for seller consultation and provider integration.
```

Outputs:

```text
post-implementation summary
known gaps list
next-phase backlog
rollback/cleanup tasks if needed
updated changelog
```

## 26. Final Acceptance Checklist

```text
[ ] Backend storage config implemented.
[ ] S3-compatible disks configured.
[ ] Public/private bucket separation verified.
[ ] Media metadata table added.
[ ] Upload-slot API implemented.
[ ] Confirm upload API implemented.
[ ] Private signed download API implemented.
[ ] Delete/discard flow implemented.
[ ] Beauty product mapping model added.
[ ] Recommendation scoring service added.
[ ] Reason/warning cards generated.
[ ] Storefront renders recommendations.
[ ] Admin/vendor mapping support or controlled seed workflow exists.
[ ] No frontend secret exposure.
[ ] Deployment smoke tests pass.
[ ] Documentation package updated.
[ ] Changelog updated.
```
