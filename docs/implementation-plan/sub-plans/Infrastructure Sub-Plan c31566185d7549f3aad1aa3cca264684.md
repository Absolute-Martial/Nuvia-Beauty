# Infrastructure Sub-Plan

Owner: Susank Shakya

<aside>
📦

Source: `implementation-plan/infrastructure-sub-plan.md` in [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

## 1. Objectives

Define infrastructure requirements for Phase 4 implementation and deployment.

## 2. Scope

**In scope:** backend runtime, frontend runtimes, MySQL, Redis, S3-compatible object storage, MinIO/AIStor deployment assumptions, HTTPS/domain routing, Docker Compose/deployment environment variables.

**Out of scope:** new cloud migration, Kubernetes migration, managed database migration, full observability platform rollout.

## 3. Tasks and activities

### 3.1 Runtime services

- Verify backend on port 8000, storefront 3003, admin-panel 3002, vendor-portal 3004.
- Verify MySQL and Redis connectivity.

### 3.2 Object storage

- Create/verify public assets, private beauty inputs, private beauty results buckets; define optional calibration bucket.
- Apply public-read only to the public bucket; keep private buckets private.
- Configure CORS for expected frontend origins; validate S3-compatible API endpoint; protect storage console access.

### 3.3 Environment variables

- Add storage provider, bucket, and TTL variables.
- Validate backend-only secret placement and frontend public URL variables.

## 4. Owners / responsibilities

| Owner | Responsibilities |
| --- | --- |
| DevOps | Storage, domains, runtime environment, deployment variables |
| Backend Engineer | Backend config and storage integration validation |
| Security Reviewer | Bucket access and secret handling review |
| QA | Smoke tests and environment verification |

## 5. Timelines

- Infrastructure verification: before implementation · Storage configuration: Phase 1 · Bucket policy validation: before media API rollout · Final smoke test: after deployment.

## 6. Tools and technologies

Docker Compose / deployment platform, MySQL, Redis, MinIO/AIStor-compatible storage, Cloudflare or equivalent routing, Let's Encrypt certificates, Laravel config commands, Browser DevTools, S3-compatible client.

## 7. Risks and dependencies

| Risk | Response |
| --- | --- |
| S3 endpoint not reachable from backend | Validate network path before API work |
| CORS blocks browser upload | Configure origins and methods before UI integration |
| Console exposed publicly | Restrict console separately from S3 API |
| Frontend URL mismatch | Validate public env variables before build |

## 8. Deliverables

Validated runtime service list, storage bucket list, bucket access policy notes, backend env variable list, frontend public env variable list, deployment smoke test record.

## 9. Approval criteria

All services reachable · backend can reach MySQL and Redis · backend can reach S3-compatible endpoint · public/private bucket behavior verified · frontend public env variables correct · storage console access reviewed.