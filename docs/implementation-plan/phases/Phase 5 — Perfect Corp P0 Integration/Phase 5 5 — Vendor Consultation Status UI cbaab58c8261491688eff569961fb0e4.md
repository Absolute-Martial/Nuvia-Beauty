# Phase 5.5 — Vendor Consultation Status UI

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-5/phase-5.5-vendor-consultation-status-ui.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) › **Phase 5.5**

## Overview

Extends the vendor consultation UI to start analysis and reflect its asynchronous status (pending → completed/failed), then display analysis-enriched recommendations.

## Objectives

- Let vendors trigger analysis from a session.
- Show live analysis status and handle failures.
- Display enriched recommendations on completion.

## Scope

**In scope**

- Vendor-portal analysis start + status polling UI.
- Integration with `POST /beauty/sessions/{id}/analysis/start` and `GET /beauty/analysis/{taskId}/status`.

**Out of scope**

- Backend orchestration ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Business Context

Vendors need clear feedback during the async analysis to keep the in-store consultation smooth and trustworthy.

## Functional Requirements

- Start analysis; poll/display status; handle `failed`.
- Show enriched recommendations when `analysis_completed`.

## Technical Requirements

- Status polling client + state-driven UI.
- Graceful error + retry handling.

## Architecture Impact

- New consumer of analysis endpoints; no backend change.

## Dependencies

- [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md), [Phase 4.4 — Vendor Consultation UI](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%204%20%E2%80%94%20Vendor%20Consultation%20UI%2061e75da0aef249179e2f443e827f909e.md).

## Detailed Implementation Tasks

- [ ]  Add “Start analysis” action to the consultation UI.
- [ ]  Poll status and render pending/completed/failed.
- [ ]  Display enriched recommendations on completion.
- [ ]  Handle errors + retry.
- [ ]  Component + integration tests.

## Deliverables

- Analysis status UI + tests.

## Testing & Validation Strategy

- Component tests for each status; integration against analysis APIs (demo mode).

## Acceptance Criteria

- Vendors can start analysis and see status + enriched results.

## Exit Criteria

- Status UI merged and demoable.

## Risks & Mitigations

- **Confusing async UX** → explicit status + progress.
- **Polling load** → sane intervals/backoff.

## Rollout Plan

- Works in demo mode; live behind gate.

## Success Metrics

- Clear status feedback; low UI error rate.

## Related Documentation

- Parent: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Vendor UI: [Phase 4.4 — Vendor Consultation UI](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%204%20%E2%80%94%20Vendor%20Consultation%20UI%2061e75da0aef249179e2f443e827f909e.md) · API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md)

## Future Considerations

- Push/websocket status updates.