# Phase 9.2 — Provider Service, Jobs & Result Media Storage

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-9/phase-9.2-provider-service-jobs-result-media-storage.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) › **Phase 9.2**

## Overview

Implements the provider service and jobs that execute makeup VTO and persist resulting try-on media to private result storage, following the established async orchestration pattern.

## Objectives

- Orchestrate VTO via the provider asynchronously.
- Persist result media to private result storage.
- Map outcomes to VTO task states.

## Scope

**In scope**

- VTO provider service + jobs; result media via `s3_beauty_results`.

**Out of scope**

- Save/discard endpoints ([Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)).

## Business Context

Reliable VTO generation + secure result storage are the core engine behind the try-on experience.

## Functional Requirements

- Start VTO task; poll until result/timeout.
- Store result media privately; link to the task.

## Technical Requirements

- VTO provider service + create/poll jobs (mirrors [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).
- Result media persisted via `s3_beauty_results` (private).

## Architecture Impact

- Adds VTO orchestration + result media handling to the beauty pipeline.

## Dependencies

- [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md).

## Detailed Implementation Tasks

- [ ]  Implement the VTO provider service.
- [ ]  Implement create/poll VTO jobs (bounded polling).
- [ ]  Persist result media to `s3_beauty_results` (private).
- [ ]  Map outcomes to VTO task states.
- [ ]  Unit + queue tests (mocked provider).

## Deliverables

- VTO service + jobs + result media storage + tests.

## Testing & Validation Strategy

- Tests for orchestration, timeout/failure, private media storage.

## Acceptance Criteria

- VTO generates + stores result media reliably and privately.

## Exit Criteria

- VTO engine merged; ready for endpoints/UI.

## Risks & Mitigations

- **Large media / cost** → retention + demo mode + bounded polling.
- **Media exposure** → private buckets + signed access.

## Rollout Plan

- Demo/disabled by default; live behind a gate.

## Success Metrics

- VTO success rate; 0 public result media.

## Related Documentation

- Parent: [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Orchestration: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) · Jobs: [[queue-jobs.md](http://queue-jobs.md)](../../../backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md)

## Future Considerations

- **Beyond roadmap:** custom VTO/analysis models via a brand portal.