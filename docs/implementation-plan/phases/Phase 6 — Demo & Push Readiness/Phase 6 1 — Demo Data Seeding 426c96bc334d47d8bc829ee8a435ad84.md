# Phase 6.1 — Demo Data Seeding

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-6/phase-6.1-demo-data-seeding.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md) › **Phase 6.1**

## Overview

Builds a curated, deterministic demo dataset that showcases the full Nuvia Beauty flow end-to-end: products, mappings, recommendations (including an avoid warning), a consultation session, and a provider analysis result.

## Objectives

- Seed a compact, realistic dataset for the demo.
- Ensure the dataset exercises recommendations + analysis.
- Make seeding repeatable and idempotent.

## Scope

**In scope**

- ~10 products, ~10 beauty mappings, 3 strong recommendations, 1 avoid warning, 1 consultation session, 1 provider analysis result (demo mode).

**Out of scope**

- Demo narration/scripting ([Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md)).

## Business Context

A tight, believable dataset is what makes the demo land with stakeholders; it must reliably reproduce the same compelling outcomes.

## Functional Requirements

- Seeders create the full demo dataset deterministically.
- Recommendations + demo analysis result are reproducible.

## Technical Requirements

- Seeder classes building products/mappings/session/result.
- Idempotent, environment-guarded (non-prod) seeding.

## Architecture Impact

- None; demo data only.

## Dependencies

- [Phase 3.7 — Demo Seed Readiness](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation/Phase%203%207%20%E2%80%94%20Demo%20Seed%20Readiness%20535bb98cda0f489aa86a8ba9ccae0805.md), [Phase 5.4 — Demo-Mode Fallback](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration/Phase%205%204%20%E2%80%94%20Demo-Mode%20Fallback%209a6d35345ec1404cad9190ba668afbe2.md).

## Detailed Implementation Tasks

- [ ]  Build product + mapping seeders for the demo set.
- [ ]  Seed a consultation session + demo provider result.
- [ ]  Verify 3 strong recs + 1 avoid warning appear.
- [ ]  Make seeding idempotent + non-prod-guarded.
- [ ]  Document the seed command.

## Deliverables

- Demo seeders + documented seed procedure.

## Testing & Validation Strategy

- Seed run produces the expected recs/warnings/session/result.

## Acceptance Criteria

- The demo dataset reproduces the intended outcomes every run.

## Exit Criteria

- Demo data ready for scripting + dry runs.

## Risks & Mitigations

- **Flaky/non-deterministic demo** → fixed seeds + demo mode.

## Rollout Plan

- Non-prod only; reused across dry runs and the live demo.

## Success Metrics

- 100% reproducible demo outcomes.

## Related Documentation

- Parent: [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Seeds: [Phase 3.7 — Demo Seed Readiness](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation/Phase%203%207%20%E2%80%94%20Demo%20Seed%20Readiness%20535bb98cda0f489aa86a8ba9ccae0805.md) · Demo mode: [Phase 5.4 — Demo-Mode Fallback](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration/Phase%205%204%20%E2%80%94%20Demo-Mode%20Fallback%209a6d35345ec1404cad9190ba668afbe2.md)

## Future Considerations

- Multiple demo personas/datasets.