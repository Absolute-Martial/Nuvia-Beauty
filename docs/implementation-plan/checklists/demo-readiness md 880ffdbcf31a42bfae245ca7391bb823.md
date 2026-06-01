# demo-readiness.md

Owner: Susank Shakya

<aside>
📄

**`docs/implementation-plan/checklists/demo-readiness.md`**

</aside>

# Demo readiness checklist

## Verified in this repo

- [x] Backend demo preparation command exists: `php artisan beauty:prepare-demo`
- [x] Backend demo audit command exists: `php artisan beauty:audit-demo-readiness`
- [x] Demo-mode fallback is verified in tests and does not require live Perfect Corp credentials
- [x] Backend Phase 5 verification passed and is recorded in `docs/implementation-plan/evidence/backend-tests ...md`
- [x] Frontend build verification passed and is recorded in `docs/implementation-plan/evidence/frontend-tests ...md`
- [x] A repeatable dry-run helper exists: `bash scripts/phase6-demo-readiness.sh`

## Required before calling the demo fully ready

- [x] Run `bash scripts/phase6-demo-readiness.sh` against the current demo environment and archive the generated JSON reports
- [ ] Rehearse the presenter flow in `docs/product/demo-scenarios.md`
- [ ] Confirm the seeded storefront catalog is visible in the demo environment
- [ ] Confirm vendor consultation analysis completes through demo mode in the demo environment
- [ ] Confirm admin beauty mapping overview and recompute controls are reachable in the demo environment
- [ ] Confirm no private media becomes publicly reachable during the rehearsal
- [ ] Record the final evidence index for the rehearsal run
- [ ] Rehearse rollback using `docs/deployment/rollback.md`

## Evidence map

- Demo script: `docs/product/demo-scenarios.md`
- Evidence index: `docs/implementation-plan/evidence/phase-6-demo-readiness.md`
- Rollback: `docs/deployment/rollback.md`
- Latest generated reports:
  - `backend-engine/storage/app/beauty/phase6-demo-preparation-report.json`
  - `backend-engine/storage/app/beauty/phase6-demo-audit-report.json`
