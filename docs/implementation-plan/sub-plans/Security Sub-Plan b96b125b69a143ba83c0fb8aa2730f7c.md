# Security Sub-Plan

Owner: Susank Shakya

<aside>
📦

Source: `implementation-plan/security-sub-plan.md` in [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

## 1. Objectives

Define security controls for Phase 4, focused on protected backend boundaries, S3-compatible media storage, private beauty/customer media, recommendation data, and frontend secret prevention.

## 2. Scope

**In scope:** backend credential handling, S3/MinIO/AIStor access control, public/private bucket separation, signed URL rules, media ownership checks, frontend public variable review, logging restrictions, beauty profile and recommendation privacy.

**Out of scope:** formal external penetration test, SOC2/ISO certification, full compliance audit, live provider compliance review.

## 3. Tasks and activities

### 3.1 Secret management

- Keep `APP_KEY`, DB credentials, S3/MinIO/AIStor credentials, and provider API keys backend-only.
- Review frontend `NEXT_PUBLIC` variables; inspect browser network requests for exposed secrets.

### 3.2 Storage security

- Verify only the public assets bucket is public-readable; private beauty buckets deny public reads.
- Use short-lived upload/download URLs; validate MIME type and file size before upload-slot creation.
- Do not use original filename as trusted object key; do not store raw media bytes in MySQL.

### 3.3 Authorization

- Require authenticated access for private media; check media ownership before signed download URL creation.
- Restrict vendor data to vendor-owned resources; restrict admin routes to admin users; audit admin access to sensitive beauty data if implemented.

### 3.4 Logging controls

**Do not log:** storage secret keys, provider API keys, full signed URLs, raw private media, raw sensitive provider payloads.

**Safe log fields:** `media_id`, `user_id`, `shop_id`, `status`, error code, provider task id if safe, object key hash/reference.

## 4. Owners / responsibilities

| Owner | Responsibilities |
| --- | --- |
| Security Reviewer | Security approval and checklist review |
| Backend Engineer | Authorization, storage services, logging controls |
| DevOps | Bucket policies, environment variables, routing protection |
| Frontend Engineer | Prevent frontend secret exposure |
| Product Owner | Confirm non-medical wording and privacy expectations |

## 5. Timelines

- Design review: before implementation · Code review: during implementation · Security validation: before deployment approval · Post-deploy check: immediately after release.

## 6. Tools and technologies

Laravel middleware/policies, Laravel validation, S3-compatible bucket policies, MinIO/AIStor console or client, Browser DevTools, server/container logs.

## 7. Risks and dependencies

| Risk | Response |
| --- | --- |
| Private media becomes public | Enforce bucket separation and test public denial |
| Credentials exposed in frontend | Review all `NEXT_PUBLIC` vars and browser bundles |
| Signed URL leakage | Avoid logging full URLs and use short TTLs |
| Vendor accesses other vendor data | Add ownership checks in backend services/policies |
| Medical claim risk | Use non-clinical recommendation language |

## 8. Deliverables

Security checklist, bucket access validation notes, frontend secret exposure review, private media authorization test notes, logging review notes.

## 9. Approval criteria

No backend secrets exposed to browser · private buckets deny public access · private media signed URLs require authorization · upload validation exists · logs avoid sensitive values · recommendation copy avoids medical diagnosis claims.