# Phase 4.3 — Session APIs

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-4/phase-4.3-session-apis.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) › **Phase 4.3**

## Overview

Exposes the consultation session APIs that drive the seller workflow: create a session, attach media, fetch recommendations, and save or discard — all governed by the Phase 4.2 state machine.

## Objectives

- Provide REST endpoints for the full session lifecycle.
- Enforce state-machine rules at the API boundary.
- Return recommendations for a session.

## Scope

**In scope**

- `POST /beauty/sessions`, `GET /beauty/sessions/{id}`, `POST /beauty/sessions/{id}/attach-media`, `POST /beauty/sessions/{id}/save`, `POST /beauty/sessions/{id}/discard`, `GET /beauty/sessions/{id}/recommendations`.

**Out of scope**

- Vendor UI ([Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)); save/discard audit details ([Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)).

## Business Context

These endpoints are the contract the vendor portal consumes; they make the consultation flow usable end-to-end.

## Functional Requirements

- Create/read sessions; attach media via storage; fetch recommendations; save/discard.
- Reject operations that violate the state machine.

## Technical Requirements

- Controllers + form requests + API resources.
- Owner-scoped authorization.
- Integration with storage (attach-media) and recommendations.

## Architecture Impact

- Defines the consultation API surface; documented in API contracts.

## Dependencies

- [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md), [Phase 1.3 — Storage APIs](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%203%20%E2%80%94%20Storage%20APIs%20ebca8cf540bb4585aeecaffdfbeba6df.md), [Phase 1.5 — Recommendation Engine](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%205%20%E2%80%94%20Recommendation%20Engine%20edbdb128ef9442a99dce738719dc2a00.md).

## Detailed Implementation Tasks

- [ ]  Implement all six session endpoints.
- [ ]  Add validation + owner-scoped authorization.
- [ ]  Wire attach-media to storage confirm.
- [ ]  Wire recommendations to the engine.
- [ ]  Feature tests for each endpoint + state guards.

## Deliverables

- Session API endpoints + feature tests + contract docs.

## Testing & Validation Strategy

- Feature tests per endpoint, including invalid-state rejections + authZ.

## Acceptance Criteria

- Full session lifecycle works via the APIs with correct guards.

## Exit Criteria

- Session APIs merged, tested, and documented.

## Risks & Mitigations

- **Inconsistent error handling** → standardized API error shape.
- **AuthZ gaps** → owner-scoped policies + tests.

## Rollout Plan

- Ship with Phase 4; consumed by vendor portal.

## Success Metrics

- 100% endpoint test coverage of lifecycle paths.

## Related Documentation

- Parent: [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · Storage: [Phase 1.3 — Storage APIs](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%203%20%E2%80%94%20Storage%20APIs%20ebca8cf540bb4585aeecaffdfbeba6df.md)

## Future Considerations

- Pagination/filtering for session history ([Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)).