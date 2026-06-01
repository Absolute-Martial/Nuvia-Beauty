# Phase 2.2 — Product Signal Aggregation & Recompute

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-2/phase-2.2-product-signal-aggregation-recompute.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) › **Phase 2.2**

## Overview

Aggregates raw beauty events into per-product signals and provides both a scheduled and on-demand recompute path. These signals enrich the recommendation engine with behavioral evidence.

## Objectives

- Aggregate events into durable product signals.
- Provide scheduled (daily) and admin-triggered recompute.
- Keep aggregation idempotent and observable.

## Scope

**In scope**

- Migration `000005_create_beauty_product_signals`.
- `BeautyProductSignal` model + `BeautyProductSignalService`.
- Job `RecomputeBeautyProductSignals` + command `RecomputeBeautyProductSignalsCommand` (`php artisan beauty:recompute-product-signals`, daily via `Console/Kernel`).
- `POST /api/v1/admin/beauty/recommendations/recompute`.

**Out of scope**

- Editor UIs ([Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md)/[Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md)).

## Business Context

Signals turn behavioral data into measurable product performance, enabling the recommendation engine to improve over time without manual retuning.

## Functional Requirements

- Recompute signals from events on schedule and on demand.
- Persist aggregated signals per product.
- Expose an admin recompute endpoint.

## Technical Requirements

- Table `beauty_product_signals`.
- Queue job + scheduled command registered in `Console/Kernel`.
- Idempotent aggregation; safe re-runs.

## Architecture Impact

- Introduces asynchronous aggregation and a scheduled workload to the Beauty domain; integrates with the recommendation scoring inputs.

## Dependencies

- [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md).

## Detailed Implementation Tasks

- [ ]  Write migration `000005_create_beauty_product_signals`.
- [ ]  Implement signal model + aggregation service.
- [ ]  Implement `RecomputeBeautyProductSignals` job + command.
- [ ]  Schedule daily recompute in `Console/Kernel`.
- [ ]  Implement admin recompute endpoint.
- [ ]  Unit + feature + scheduled-run tests.

## Deliverables

- `beauty_product_signals` migration + model.
- Recompute job, command, schedule, and admin endpoint.

## Testing & Validation Strategy

- Unit: aggregation correctness + idempotency.
- Feature: admin recompute endpoint.
- Scheduler: command runs and updates signals.

## Acceptance Criteria

- Signals recompute correctly on schedule and on demand; re-runs are safe.

## Exit Criteria

- Aggregation + recompute merged, scheduled, and tested.

## Risks & Mitigations

- **Expensive recompute** → incremental aggregation + batching.
- **Stale signals** → daily schedule + admin trigger.

## Rollout Plan

- Ship with Phase 2; enable scheduler in non-prod first.

## Success Metrics

- Recompute completes within target window; signal freshness < 24h.

## Related Documentation

- Parent: [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Queue/jobs: [[queue-jobs.md](http://queue-jobs.md)](../../../backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md) · Engine: [Phase 1.5 — Recommendation Engine](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%205%20%E2%80%94%20Recommendation%20Engine%20edbdb128ef9442a99dce738719dc2a00.md)

## Future Considerations

- Real-time/streaming signal updates.
- Signal-weighted scoring tuning.