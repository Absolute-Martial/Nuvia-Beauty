# Phase 0: Baseline Verification

## Status

```text
Required before production rollout.
```

## Objective

Confirm the actual repository, runtime, deployment, and documentation state before continuing implementation.

## Scope

In scope:

```text
repo structure verification
current branch/commit verification
backend route inventory
storage config inventory
database migration inventory
frontend build baseline
deployed endpoint verification
```

Out of scope:

```text
new feature implementation
Perfect Corp integration
seller consultation implementation
```

## Required checks

### Repository structure

Confirm:

```text
backend-engine/ = Laravel backend
storefront/ = customer Next.js app
admin-panel/ = admin Next.js app
vendor-portal/ = vendor Next.js app
docs/ = architecture and implementation documentation
```

Reject implementation paths that create:

```text
apps/web
apps/mobile
Prisma
PostgreSQL
Next.js API routes for backend business logic
```

### Git state

Run:

```bash
git status
git branch --show-current
git log --oneline -5
git remote -v
```

Expected:

```text
working tree understood
branch known
remote points to Absolute-Martial/Nuvia-Beauty
```

### Backend baseline

Run where shell is available:

```bash
cd backend-engine
php artisan config:clear
php artisan migrate
php artisan route:list --path=api/v1
```

If `route:list` fails due to DB-backed settings, fix that before continuing.

### Frontend baseline

Run from repository root:

```bash
yarn build:storefront
yarn build:admin-panel
yarn build:vendor-portal
```

## Deliverables

```text
confirmed repo structure
current commit references
route inventory
migration status
frontend build status
known blockers list
```

## Acceptance criteria

```text
Repo structure confirmed.
Git remote authentication issue resolved or documented.
Backend artisan commands work.
Frontend builds pass.
No incorrect apps-web/Prisma/Postgres path is introduced.
```

## Known current blocker

Reported local implementation results state that remote push failed because Git authenticated as `Shadow-Martial` while the target repository is `Absolute-Martial/Nuvia-Beauty`.

This must be fixed before production review.
