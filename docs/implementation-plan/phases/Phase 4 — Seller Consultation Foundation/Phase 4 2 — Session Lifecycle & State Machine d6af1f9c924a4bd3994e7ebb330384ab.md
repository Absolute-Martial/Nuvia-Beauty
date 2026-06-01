# Phase 4.2 — Session Lifecycle & State Machine

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-4/phase-4.2-session-lifecycle-state-machine.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) › **Phase 4.2**

## Overview

Defines and enforces the consultation session state machine: `draft → media_uploaded → analysis_pending → analysis_completed → saved | discarded | failed`. Encodes valid transitions and guards to keep sessions consistent.

## Objectives

- Model explicit session states and transitions.
- Enforce valid transitions with guards.
- Make state changes observable/auditable.

## Scope

**In scope**

- State enum + transition rules on `beauty_sessions`.
- Transition guards + audit hooks.

**Out of scope**

- API surface ([Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)).

## Business Context

A strict lifecycle prevents invalid states (e.g. saving before analysis), making the seller flow predictable and recoverable.

## Functional Requirements

- Only valid transitions are permitted.
- Invalid transitions are rejected with clear errors.
- Transitions write audit entries.

## Technical Requirements

- State machine in the session service.
- Guard methods per transition; integrate with audit logs.
- Failure state handling for analysis errors.

## Architecture Impact

- Centralizes session integrity; provider analysis ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)) plugs into the pending/completed/failed states.

## Dependencies

- [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md).

## Detailed Implementation Tasks

- [ ]  Define the state enum + transition table.
- [ ]  Implement guarded transition methods.
- [ ]  Emit audit events on transitions.
- [ ]  Handle the `failed` path.
- [ ]  Unit tests for all valid/invalid transitions.

## Deliverables

- Session state machine + transition tests.

## Testing & Validation Strategy

- Unit tests covering each transition and rejection.

## Acceptance Criteria

- All valid transitions succeed; invalid ones are rejected and audited.

## Exit Criteria

- State machine merged; ready to expose via APIs.

## Risks & Mitigations

- **Stuck sessions** → explicit `failed` + recovery transitions.
- **Race conditions** → transactional transitions/locking.

## Rollout Plan

- Backend; ship with Phase 4.

## Success Metrics

- 0 invalid-state occurrences in testing.

## Related Documentation

- Parent: [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Provider analysis: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)

## Future Considerations

- Configurable lifecycles for additional consultation types.