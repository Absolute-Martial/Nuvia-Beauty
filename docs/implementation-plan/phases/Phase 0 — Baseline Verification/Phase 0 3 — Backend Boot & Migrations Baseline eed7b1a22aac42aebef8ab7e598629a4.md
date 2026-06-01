# Phase 0.3 — Backend Boot & Migrations Baseline

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-0/phase-0.3-backend-boot-migrations-baseline.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) › **Phase 0.3**

## Overview

Confirms the Laravel backend boots, can run console commands, and applies migrations cleanly. This sub-phase surfaces the known console-boot blocker (DB-backed settings accessed during boot can break `php artisan` commands such as `route:list`) so it is documented and scheduled for remediation.

## Objectives

- Verify the backend boots in web and console contexts.
- Confirm migrations run cleanly on a fresh database.
- Document the console-boot blocker and its remediation path.

## Scope

**In scope**

- `php artisan` console boot, `migrate`, and `route:list` behavior.
- Database connectivity and migration integrity.

**Out of scope**

- Fixing the console-boot blocker (remediated in [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)).

## Business Context

A bootable backend with reliable migrations is foundational; the console-boot blocker currently impedes automated tooling and CI, so capturing it early protects later validation phases.

## Functional Requirements

- The API serves requests.
- `php artisan migrate` succeeds on a clean DB.
- Console commands either run or the blocker is clearly documented.

## Technical Requirements

- MySQL 8 reachable with valid credentials.
- Migrations idempotent and ordered.
- Note: settings read during console boot must tolerate an unmigrated/empty DB.

## Architecture Impact

- Confirms the persistence layer and Laravel bootstrap. Highlights a boot-time coupling between settings and the database to be decoupled in Phase 3.

## Dependencies

- [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) (MySQL reachable).

## Detailed Implementation Tasks

- [ ]  Boot the API and confirm a basic route responds.
- [ ]  Run `php artisan migrate:fresh` on a scratch DB; capture output.
- [ ]  Attempt `php artisan route:list`; record success or the boot error.
- [ ]  Document the DB-backed-settings boot coupling and link to [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md).

## Deliverables

- Migration run evidence.
- Documented console-boot blocker with reproduction steps.

## Testing & Validation Strategy

- Fresh-DB migration test.
- Console command probe (`route:list`, `config:cache`).

## Acceptance Criteria

- Migrations run cleanly on a fresh DB.
- Console-boot status is known and documented.

## Exit Criteria

- Backend boot + migration baseline recorded; blocker scheduled.

## Risks & Mitigations

- **Console boot fails on `route:list`** → document now; decouple settings from boot in [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md).
- **Migration ordering issues** → verify on fresh DB and fix ordering.

## Rollout Plan

- Local + CI baseline. No production impact.

## Success Metrics

- Fresh-DB migration success rate 100%.
- Console-boot blocker reproduced and documented.

## Related Documentation

- Parent: [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · Stabilization: [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)

## Future Considerations

- Add a CI job that runs `migrate:fresh` and `route:list` on every PR once the blocker is fixed.