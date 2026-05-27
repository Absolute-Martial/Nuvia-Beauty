# Testing Sub-Plan

## 1. Objectives

Validate that Phase 4 works correctly, securely, and reliably across backend, frontend, storage, and deployment environments.

## 2. Scope

In scope:

```text
backend API tests
migration validation
storage access validation
recommendation scoring validation
frontend UI verification
deployment smoke tests
security checks for credentials and private media
```

Out of scope:

```text
full AI provider accuracy testing
load testing at production scale
native mobile testing
payment settlement testing
```

## 3. Tasks and Activities

### 3.1 Backend Validation

```text
Run Laravel config clear/cache checks.
Run migrations.
Verify route list.
Test upload-slot validation.
Test upload confirmation.
Test private signed-download authorization.
Test media delete/discard status transitions.
Test recommendation generation.
Test warning/reason output.
```

### 3.2 Storage Validation

```text
Verify public bucket object is readable.
Verify private bucket object is not publicly readable.
Verify signed PUT works.
Verify backend HEAD object check works.
Verify signed GET expires.
Verify delete/discard flow updates metadata.
```

### 3.3 Frontend Validation

```text
Storefront loads.
Recommendation cards render.
Reason list renders.
Warning list renders.
Vendor/admin mapping workflow or seed flow works.
No backend credentials appear in browser-visible config.
```

### 3.4 Deployment Smoke Tests

```text
Storefront URL reachable.
Admin URL reachable.
Vendor URL reachable.
Backend API reachable.
S3 API reachable.
S3 console protected.
```

## 4. Owners / Responsibilities

| Owner | Responsibilities |
|---|---|
| Backend Engineer | API and migration tests |
| Frontend Engineer | UI verification |
| DevOps | Deployment and storage checks |
| Security Reviewer | Secret and private-media checks |
| Product Owner | Recommendation quality review |

## 5. Timelines

```text
Unit/API tests: during development
Manual integration tests: after each phase
Deployment smoke tests: after staging deploy
Security validation: before release
```

## 6. Tools and Technologies

```text
Laravel artisan commands
Laravel/PHP test tooling where available
Browser DevTools
Postman/Insomnia or curl
MinIO/AIStor console or S3-compatible client
Docker/Dokploy logs
```

## 7. Risks and Dependencies

| Risk | Response |
|---|---|
| No automated coverage for existing package routes | Add manual route and smoke checklist |
| Storage policy misread | Test public and private URLs separately |
| Recommendation quality subjective | Use product-owner review with fixed sample profiles |
| Environment mismatch | Test local and deployed environments separately |

## 8. Deliverables

```text
test checklist
API test results
manual QA notes
storage validation evidence
security validation notes
release readiness decision
```

## 9. Approval Criteria

```text
All critical smoke tests pass.
Private bucket denies public access.
Signed URL flows work.
Recommendation endpoint returns valid scored output.
Frontend renders recommendation cards.
No frontend secret exposure detected.
```
