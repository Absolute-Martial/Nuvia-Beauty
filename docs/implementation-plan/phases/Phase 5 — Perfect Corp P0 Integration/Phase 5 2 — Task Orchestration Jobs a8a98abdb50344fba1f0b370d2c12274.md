# Phase 5.2 — Task Orchestration Jobs

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-5/phase-5.2-task-orchestration-jobs.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) › **Phase 5.2**

## Overview

Implements the asynchronous task orchestration for provider analysis: creating an analysis task and polling for its result, with retry/backoff bounded by the provider config.

## Objectives

- Create provider analysis tasks asynchronously.
- Poll for completion within bounded attempts.
- Map provider outcomes to session task states.

## Scope

**In scope**

- `PerfectCorpTaskService`, jobs `CreatePerfectCorpAnalysisTask`, `PollPerfectCorpAnalysisTask`.
- Polling bounded by `POLL_INTERVAL_SECONDS` / `MAX_ATTEMPTS`.

**Out of scope**

- Result normalization/storage ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Business Context

Provider analysis is asynchronous; robust orchestration ensures sessions progress reliably and surface failures cleanly.

## Functional Requirements

- Create task → transition session to `analysis_pending`.
- Poll until completion/timeout; handle `failed`.

## Technical Requirements

- Queue jobs with retry/backoff; idempotent polling.
- Integration with the session state machine ([Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)).

## Architecture Impact

- Adds queue-driven provider orchestration to the Beauty domain.

## Dependencies

- [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md), [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md).

## Detailed Implementation Tasks

- [ ]  Implement `PerfectCorpTaskService`.
- [ ]  Implement `CreatePerfectCorpAnalysisTask` job.
- [ ]  Implement `PollPerfectCorpAnalysisTask` with bounded attempts.
- [ ]  Map outcomes to session states (incl. `failed`).
- [ ]  Unit + queue tests (mocked provider).

## Deliverables

- Task service + create/poll jobs + tests.

## Testing & Validation Strategy

- Unit/queue tests for create, poll success, timeout, and failure.

## Acceptance Criteria

- Tasks orchestrate reliably with bounded polling and correct state mapping.

## Exit Criteria

- Orchestration merged; ready for result handling.

## Risks & Mitigations

- **Infinite polling** → `MAX_ATTEMPTS` + timeout → `failed`.
- **Duplicate tasks** → idempotency keys.

## Rollout Plan

- Disabled/demo by default; live behind gate.

## Success Metrics

- Bounded poll completion; 0 stuck tasks.

## Related Documentation

- Parent: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Queue/jobs: [[queue-jobs.md](http://queue-jobs.md)](../../../backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md) · State machine: [Phase 4.2 — Session Lifecycle & State Machine](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%202%20%E2%80%94%20Session%20Lifecycle%20&%20State%20Machine%20d6af1f9c924a4bd3994e7ebb330384ab.md)

## Future Considerations

- Webhooks to replace polling where supported.