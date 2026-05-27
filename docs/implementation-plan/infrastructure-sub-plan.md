# Infrastructure Sub-Plan

## 1. Objectives

Define infrastructure requirements for Phase 4 implementation and deployment.

## 2. Scope

In scope:

```text
backend runtime
frontend runtimes
MySQL
Redis
S3-compatible object storage
MinIO/AIStor deployment assumptions
HTTPS/domain routing
Docker Compose/deployment environment variables
```

Out of scope:

```text
new cloud migration
Kubernetes migration
managed database migration
full observability platform rollout
```

## 3. Tasks and Activities

### 3.1 Runtime Services

```text
Verify backend service on port 8000.
Verify storefront on port 3003.
Verify admin panel on port 3002.
Verify vendor portal on port 3004.
Verify MySQL connectivity.
Verify Redis connectivity.
```

### 3.2 Object Storage

```text
Create or verify public assets bucket.
Create or verify private beauty inputs bucket.
Create or verify private beauty results bucket.
Define optional calibration bucket.
Apply public-read only to public bucket.
Keep private buckets private.
Configure CORS for expected frontend origins.
Validate S3-compatible API endpoint.
Protect storage console access.
```

### 3.3 Environment Variables

```text
Add storage provider variables.
Add bucket variables.
Add TTL variables.
Validate backend-only secret placement.
Validate frontend public URL variables.
```

## 4. Owners / Responsibilities

| Owner | Responsibilities |
|---|---|
| DevOps | Storage, domains, runtime environment, deployment variables |
| Backend Engineer | Backend config and storage integration validation |
| Security Reviewer | Bucket access and secret handling review |
| QA | Smoke tests and environment verification |

## 5. Timelines

```text
Infrastructure verification: before implementation.
Storage configuration: Phase 1.
Bucket policy validation: before media API rollout.
Final smoke test: after deployment.
```

## 6. Tools and Technologies

```text
Docker Compose / deployment platform
MySQL
Redis
MinIO / AIStor-compatible storage
Cloudflare or equivalent routing
Let's Encrypt certificates
Laravel config commands
Browser DevTools
S3-compatible client where available
```

## 7. Risks and Dependencies

| Risk | Response |
|---|---|
| S3 endpoint not reachable from backend | Validate network path before API work |
| CORS blocks browser upload | Configure origins and methods before UI integration |
| Console exposed publicly | Restrict console separately from S3 API |
| Frontend URL mismatch | Validate public env variables before build |

## 8. Deliverables

```text
validated runtime service list
storage bucket list
bucket access policy notes
backend env variable list
frontend public env variable list
deployment smoke test record
```

## 9. Approval Criteria

```text
All services reachable.
Backend can reach MySQL and Redis.
Backend can reach S3-compatible endpoint.
Public/private bucket behavior verified.
Frontend public env variables correct.
Storage console access reviewed.
```
