# Phase 4.4 — Vendor Consultation UI

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-4/phase-4.4-vendor-consultation-ui.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) › **Phase 4.4**

## Overview

Builds the vendor-portal consultation UI so sellers can run an in-store consultation: start a session, capture/attach media, view recommendations, and save or discard the result.

## Objectives

- Provide an end-to-end vendor consultation workflow UI.
- Integrate with the session APIs and storage.
- Surface recommendations clearly, including avoid warnings.

## Scope

**In scope**

- Vendor-portal (port 3004) consultation screens + state-driven UX.

**Out of scope**

- Customer self-scan ([Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)); provider analysis UI status ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Business Context

The vendor UI is where the consultation value is delivered in-store; it must be fast, clear, and resilient.

## Functional Requirements

- Start session, capture/upload media, view recommendations, save/discard.
- Reflect session state and handle errors gracefully.

## Technical Requirements

- Vendor-portal components + typed API client.
- Media upload via signed URLs; state-driven screens.
- Owner-scoped data only.

## Architecture Impact

- New consumer of the session APIs; no backend change.

## Dependencies

- [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md).

## Detailed Implementation Tasks

- [ ]  Build session start + media capture/upload UI.
- [ ]  Build recommendations view with avoid warnings.
- [ ]  Implement save/discard actions + state handling.
- [ ]  Handle loading/error/empty states.
- [ ]  Component + integration tests.

## Deliverables

- Vendor consultation UI + tests.

## Testing & Validation Strategy

- Component tests for each screen; integration against session APIs.

## Acceptance Criteria

- A vendor can complete a full consultation in the UI.

## Exit Criteria

- Vendor consultation UI merged and demoable.

## Risks & Mitigations

- **Upload UX friction** → clear progress + retry.
- **State desync** → drive UI from server state.

## Rollout Plan

- Vendor-scoped; ship with Phase 4.

## Success Metrics

- Consultation completion rate in testing; low error rate.

## Related Documentation

- Parent: [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- APIs: [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) · Vendor portal: [Phase 1.6 — Storefront Recommendation UI](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%206%20%E2%80%94%20Storefront%20Recommendation%20UI%202c369377d1a94fd8a2571317e73efff9.md)

## Future Considerations

- Provider-analysis status display ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).