# Phase 9.3 — Save/Discard Endpoints & Demo Mode

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-9/phase-9.3-save-discard-endpoints-demo-mode.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) › **Phase 9.3**

## Overview

Exposes the try-on API surface — start, status, results, and save/discard — plus a deterministic demo mode that returns synthetic try-on media without live provider calls.

## Objectives

- Provide start/status/results + save/discard endpoints.
- Support demo mode with synthetic try-on results.
- Enforce consent + eligibility at the edge.

## Scope

**In scope**

- `POST /beauty/sessions/{id}/try-on/start`, `GET /beauty/try-on/{taskId}/status`, `GET .../try-on-results`, `POST` save/discard; demo-mode fallback.

**Out of scope**

- Try-on UI ([Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)).

## Business Context

A clean API + demo mode enable the try-on UI to be built and demoed safely without provider dependencies.

## Functional Requirements

- Start VTO; poll status; fetch results; save/discard.
- Demo mode returns deterministic synthetic media.

## Technical Requirements

- Endpoints with eligibility + consent checks.
- Demo-mode branch mirroring live flow.

## Architecture Impact

- Public try-on API surface over the VTO engine.

## Dependencies

- [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md).

## Detailed Implementation Tasks

- [ ]  Implement try-on start/status/results endpoints.
- [ ]  Implement save/discard endpoints.
- [ ]  Implement demo-mode synthetic results.
- [ ]  Enforce eligibility + consent.
- [ ]  Feature tests (incl. demo mode).

## Deliverables

- Try-on API + demo mode + tests.

## Testing & Validation Strategy

- Feature tests for each endpoint + demo-mode determinism.

## Acceptance Criteria

- Try-on flow works end-to-end via API in demo mode.

## Exit Criteria

- Endpoints + demo mode merged.

## Risks & Mitigations

- **Live cost/dependency in demos** → demo mode default.

## Rollout Plan

- Demo mode first; live behind a gate.

## Success Metrics

- Endpoint success; reproducible demo results.

## Related Documentation

- Parent: [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · Demo pattern: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)

## Future Considerations

- **Beyond roadmap:** manufacturer feedback loop on saved try-ons.