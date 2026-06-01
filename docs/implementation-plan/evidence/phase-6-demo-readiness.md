# phase-6-demo-readiness.md

Owner: Susank Shakya

<aside>
📄

**`docs/implementation-plan/evidence/phase-6-demo-readiness.md`** — Phase 6 evidence index and verification ledger.

</aside>

# Phase 6 evidence index

## Scope

This page maps Phase 6 deliverables to the strongest current evidence in the repository.

## Deliverable map

| Phase 6 area | Requirement | Current evidence | Status |
| --- | --- | --- | --- |
| 6.1 Demo data seeding | Deterministic demo dataset can be prepared on a non-prod database | `Tests\\Feature\\Beauty\\BeautyDemoPreparationCommandTest`, `backend-engine/app/Console/Commands/BeautyPrepareDemoCommand.php`, `scripts/phase6-demo-readiness.sh` | Verified locally |
| 6.1 Demo data seeding | Seeded demo catalog uses curated public beauty dataset samples and discloses those sources in the report | `backend-engine/database/seeders/data/phase6-demo-catalog.php`, `backend-engine/storage/app/beauty/phase6-demo-preparation-report.json` | Verified locally |
| 6.1 Demo data seeding | Demo audit proves 10 mappings, saved session, completed analysis, 3 strong recs, 1 warning | `Tests\\Feature\\Beauty\\BeautyDemoPreparationCommandTest`, `backend-engine/app/Console/Commands/BeautyAuditDemoReadinessCommand.php` | Verified locally |
| 6.1 Demo access | Demo prep can provision deterministic vendor/admin credentials for deployed rehearsals when env vars are set | `Tests\\Feature\\Beauty\\BeautyDemoPreparationCommandTest`, `backend-engine/config/services.php`, `backend-engine/.env.example` | Verified locally |
| 6.2 Demo flow scripting | Presenter can follow a timed, repeatable script using built features only | `docs/product/demo-scenarios.md` | Updated, requires live rehearsal |
| 6.2 Demo flow scripting | Deployed demo environment can be smoke-checked with stable demo credentials | `scripts/phase6-deployment-smoke.sh` | Implemented, requires live credentials + deployment |
| 6.3 QA evidence capture | Verification outputs are stored and reviewable | `docs/implementation-plan/evidence/backend-tests ...md`, `docs/implementation-plan/evidence/frontend-tests ...md`, `docs/implementation-plan/evidence/storage-tests ...md` | Verified locally |
| 6.4 Deployment notes & checklist | Demo deployment and rollback instructions are documented | `docs/deployment/production-readiness.md`, `docs/deployment/rollback.md`, `docs/implementation-plan/checklists/demo-readiness.md` | Documented, rehearsal pending |
| 6.5 Presentation scenarios | Audience-specific scenarios exist and only reference implemented features | `docs/product/demo-scenarios.md` | Updated, stakeholder approval pending |

## Commands verified on 2026-06-02

- `bash scripts/phase6-demo-readiness.sh`
- `bash -n scripts/phase6-deployment-smoke.sh`
- `php artisan test --filter=BeautyDemoPreparationCommandTest`
- `php artisan test --filter=PerfectCorpAnalysisFlowTest`
- `php artisan test`
- `yarn build:vendor-portal`
- `yarn build:admin-panel`
- `yarn build:storefront`
- `bash scripts/phase6-deployment-smoke.sh` (script implemented; live run still requires deployed demo credentials and URLs)

## Latest dry-run result

Command:

```bash
bash scripts/phase6-demo-readiness.sh
```

Result:

- passed
- prepared a dedicated demo shop: `Nuvia Demo Beauty`
- refreshed the demo catalog from curated public beauty dataset samples
- generated `10` recommendations
- generated `3` strong recommendations
- generated `2` warning recommendations
- audit finished with all checks passing

Reports written to:

- `backend-engine/storage/app/beauty/phase6-demo-preparation-report.json`
- `backend-engine/storage/app/beauty/phase6-demo-audit-report.json`

## Remaining manual readiness items

- Run `bash scripts/phase6-demo-readiness.sh` against the current demo environment and archive the generated JSON reports.
- Redeploy the current branch with `BEAUTY_DEMO_ALLOW_PRODUCTION=true`, `BEAUTY_DEMO_AUTO_PREPARE=true`, and the deterministic `BEAUTY_DEMO_*_PASSWORD` credentials.
- Run `bash scripts/phase6-deployment-smoke.sh` against that deployed environment with the configured demo credentials.
- Rehearse the storefront, vendor, and admin demo script end to end after the smoke report passes.
- Validate the rollback checklist in an environment that mirrors the demo deployment.
