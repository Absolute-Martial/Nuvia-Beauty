# Deployment Sub-Plan

## 1. Objectives

Deploy Phase 4 safely to the existing Nuvia Beauty environment with clear validation, rollback, and stakeholder communication.

## 2. Scope

In scope:

```text
backend deployment
frontend deployment
storage environment variables
bucket configuration validation
migration execution
smoke testing
release notes
```

Out of scope:

```text
new cloud provider migration
full production observability platform
live AI provider rollout
```

## 3. Tasks and Activities

### 3.1 Pre-Deployment

```text
Confirm branch and commit.
Confirm APP_KEY and backend env.
Confirm DB credentials.
Confirm Redis config.
Confirm S3-compatible endpoint.
Confirm bucket names and policies.
Confirm frontend public API URLs.
Confirm rollback version.
```

### 3.2 Backend Deployment

```text
Build backend image.
Deploy backend container.
Run migrations.
Clear Laravel config/cache.
Verify route list.
Verify storage connectivity.
```

### 3.3 Frontend Deployment

```text
Build storefront.
Build admin panel.
Build vendor portal.
Deploy containers.
Verify public routes.
Verify backend API connectivity.
```

### 3.4 Post-Deployment Smoke Test

```text
Open storefront.
Open admin panel.
Open vendor portal.
Call backend health/API route.
Test storage upload-slot flow.
Test recommendation endpoint.
Inspect browser network for exposed secrets.
```

## 4. Owners / Responsibilities

| Owner | Responsibilities |
|---|---|
| DevOps | Deployment, env, routing, containers |
| Backend Engineer | Migrations, API validation, storage validation |
| Frontend Engineer | Frontend build and smoke checks |
| QA | End-to-end validation |
| Product Owner | Release approval |

## 5. Timelines

```text
Pre-deploy checks: 0.5 day
Deployment: 0.5 day
Smoke testing: 0.5 day
Post-deployment monitoring: 1 day
```

## 6. Tools and Technologies

```text
Docker Compose / deployment platform
Dokploy or server deployment interface
Laravel artisan commands
Cloudflare/HTTPS routing
MinIO/AIStor console or S3-compatible client
Browser DevTools
```

## 7. Risks and Dependencies

| Risk | Response |
|---|---|
| Migration failure | Backup DB and keep rollback plan ready |
| Storage env wrong | Validate endpoint before release |
| Frontend URL mismatch | Validate NEXT_PUBLIC variables before build |
| Private bucket public by mistake | Run access-denial test before approval |

## 8. Deliverables

```text
deployed backend
deployed frontends
executed migrations
validated storage config
smoke test record
release notes
```

## 9. Approval Criteria

```text
All service URLs reachable.
Migrations completed.
Backend routes respond.
Storage public/private tests pass.
Recommendation UI works.
No critical logs/errors after deployment window.
```
