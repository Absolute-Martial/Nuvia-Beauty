# Phase 3: Stabilization and Validation

## Status

```text
Next required phase before production or Perfect Corp work.
```

## Objective

Stabilize the locally reported Phase 1 and Phase 2 implementation, resolve push/auth and console boot blockers, and validate the full backend/frontend/storage path.

## Scope

In scope:

```text
GitHub authentication fix
push development branch
Laravel console boot fix
route registration verification
migration validation
frontend build validation
S3 positive upload/download validation
demo seed data creation
staging smoke test
```

Out of scope:

```text
new product features
Perfect Corp integration
seller consultation implementation
production push without review
```

## Required tasks

### 1. Secure local work

```bash
git status
git log --oneline -5
git tag phase4-local-cd2983c cd2983c || true
git diff de98e00~1..cd2983c > phase4-full-local.patch
```

### 2. Fix GitHub authentication

```bash
gh auth status
gh auth logout
gh auth login
gh auth setup-git
git remote set-url origin https://github.com/Absolute-Martial/Nuvia-Beauty.git
printf "protocol=https\nhost=github.com\n" | git credential reject
```

Push development only:

```bash
git checkout development
git push origin development
```

Do not push production directly.

### 3. Fix Laravel console boot issue

Issue:

```text
php artisan route:list is blocked by DB-backed settings during console startup.
```

Expected fix:

```text
keep DB-backed settings
add safe defaults/fallback when table or DB is unavailable
ensure console commands work before seed/settings rows exist
```

Required commands:

```bash
cd backend-engine
php artisan config:clear
php artisan migrate
php artisan route:list --path=api/v1
php artisan list | grep beauty
```

### 4. Frontend validation

```bash
yarn build:storefront
yarn build:admin-panel
yarn build:vendor-portal
```

### 5. Storage validation

Verify:

```text
valid upload-slot returns signed PUT URL
PUT file to signed URL works
confirm endpoint verifies object
private direct object URL fails
signed download URL works
```

### 6. Demo seed readiness

Create or confirm:

```text
minimum 10 products
minimum 10 beauty_product_mappings
at least 3 recommendation-ready products
at least 1 product with avoid-tag warning
at least 1 high-confidence recommendation case
```

## Acceptance criteria

```text
Phase 1/2 commits pushed to development.
route:list works.
Migrations run.
All frontend builds pass.
Storage positive flow works.
Private bucket blocks public read.
Recommendation endpoint works with demo data.
Admin/vendor mapping editors work.
Event ingestion and recompute work.
Docs match implementation.
```

## Gate to next phase

Do not start seller consultation or Perfect Corp integration until Phase 3 acceptance criteria pass.
