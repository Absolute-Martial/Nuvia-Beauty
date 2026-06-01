# Phase 2.5 — Documentation Updates

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-2/phase-2.5-documentation-updates.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) › **Phase 2.5**

## Overview

Updates the canonical documentation to reflect the new events, signals, recompute workflow, and mapping editors introduced in Phase 2 — keeping API contracts, schema, and queue/job docs authoritative.

## Objectives

- Document the event/signal model and recompute workflow.
- Update API contracts and schema docs.
- Record the daily recompute schedule and admin endpoint.

## Scope

**In scope**

- Updates to API contracts, database schema, and queue/jobs docs for Phase 2 additions.

**Out of scope**

- Net-new feature work.

## Business Context

Accurate documentation prevents drift and lets engineering, product, and operations execute and operate Phase 2 capabilities confidently.

## Functional Requirements

- Event/signal endpoints and schemas are documented.
- Recompute command/schedule is documented for operations.

## Technical Requirements

- Update [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md), [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md), [[queue-jobs.md](http://queue-jobs.md)](../../../backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md).
- Keep examples consistent with implemented endpoints.

## Architecture Impact

- None to runtime; maintains documentation integrity.

## Dependencies

- [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md)–[Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md).

## Detailed Implementation Tasks

- [ ]  Document `POST /beauty/events` and the events schema.
- [ ]  Document signals table + recompute job/command/schedule.
- [ ]  Document `POST /admin/beauty/recommendations/recompute`.
- [ ]  Document admin/vendor mapping editor behavior + authorization.
- [ ]  Cross-link from phase/sub-phase pages.

## Deliverables

- Updated API/schema/queue docs reflecting Phase 2.

## Testing & Validation Strategy

- Doc review against implemented endpoints.
- Link-check across phase pages.

## Acceptance Criteria

- Docs accurately describe all Phase 2 capabilities.

## Exit Criteria

- Documentation updated, reviewed, and cross-linked.

## Risks & Mitigations

- **Doc drift** → update docs in the same PRs as the features.

## Rollout Plan

- Merge alongside Phase 2 features.

## Success Metrics

- 0 undocumented Phase 2 endpoints; link-check passes.

## Related Documentation

- Parent: [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Backend: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · [[queue-jobs.md](http://queue-jobs.md)](../../../backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md)

## Future Considerations

- Auto-generated API docs from code.
- Documentation linting in CI.