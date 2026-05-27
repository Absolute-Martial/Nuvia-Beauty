# Security Sub-Plan

## 1. Objectives

Define security controls for Phase 4 implementation, focused on protected backend boundaries, S3-compatible media storage, private beauty/customer media, recommendation data, and frontend secret prevention.

## 2. Scope

In scope:

```text
backend credential handling
S3/MinIO/AIStor access control
public/private bucket separation
signed URL rules
media ownership checks
frontend public variable review
logging restrictions
beauty profile and recommendation privacy
```

Out of scope:

```text
formal external penetration test
SOC2/ISO certification
full compliance audit
live provider compliance review
```

## 3. Tasks and Activities

### 3.1 Secret Management

```text
Keep APP_KEY backend-only.
Keep DB credentials backend-only.
Keep S3/MinIO/AIStor credentials backend-only.
Keep provider API keys backend-only.
Review frontend NEXT_PUBLIC variables.
Inspect browser network requests for exposed secrets.
```

### 3.2 Storage Security

```text
Verify only public assets bucket is public-readable.
Verify private beauty buckets deny public reads.
Use short-lived upload and download URLs.
Validate MIME type and file size before upload slot creation.
Do not use original filename as trusted object key.
Do not store raw media bytes in MySQL.
```

### 3.3 Authorization

```text
Require authenticated access for private media.
Check media ownership before signed download URL creation.
Restrict vendor data to vendor-owned resources.
Restrict admin routes to admin users.
Audit admin access to sensitive beauty data if implemented.
```

### 3.4 Logging Controls

Do not log:

```text
storage secret keys
provider API keys
full signed URLs
raw private media
raw sensitive provider payloads
```

Safe log fields:

```text
media_id
user_id
shop_id
status
error code
provider task id if safe
object key hash/reference
```

## 4. Owners / Responsibilities

| Owner | Responsibilities |
|---|---|
| Security Reviewer | Security approval and checklist review |
| Backend Engineer | Authorization, storage services, logging controls |
| DevOps | Bucket policies, environment variables, routing protection |
| Frontend Engineer | Prevent frontend secret exposure |
| Product Owner | Confirm non-medical wording and privacy expectations |

## 5. Timelines

```text
Design review: before implementation.
Code review: during implementation.
Security validation: before deployment approval.
Post-deploy check: immediately after release.
```

## 6. Tools and Technologies

```text
Laravel middleware/policies
Laravel validation
S3-compatible bucket policies
MinIO/AIStor console or client
Browser DevTools
server/container logs
```

## 7. Risks and Dependencies

| Risk | Response |
|---|---|
| Private media becomes public | Enforce bucket separation and test public denial |
| Credentials exposed in frontend | Review all NEXT_PUBLIC vars and browser bundles |
| Signed URL leakage | Avoid logging full URLs and use short TTLs |
| Vendor accesses other vendor data | Add ownership checks in backend services/policies |
| Medical claim risk | Use non-clinical recommendation language |

## 8. Deliverables

```text
security checklist
bucket access validation notes
frontend secret exposure review
private media authorization test notes
logging review notes
```

## 9. Approval Criteria

```text
No backend secrets exposed to browser.
Private buckets deny public access.
Private media signed URLs require authorization.
Upload validation exists.
Logs avoid sensitive values.
Recommendation copy avoids medical diagnosis claims.
```
