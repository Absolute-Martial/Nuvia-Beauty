# Phase 1.6 — Storefront Recommendation UI

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-1/phase-1.6-storefront-recommendation-ui.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) › **Phase 1.6**

## Overview

Delivers the storefront UI that lets shoppers provide their beauty attributes and view ranked, explainable recommendations (including avoid-tag warnings) produced by the engine in [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md).

## Objectives

- Provide an attribute input experience on the storefront.
- Display ranked recommendations with rationale and warnings.
- Integrate cleanly with the recommendation API.

## Scope

**In scope**

- Storefront (Next.js, port 3003) recommendation flow and components.
- API integration for generate + fetch.

**Out of scope**

- Consultation/seller flows ([Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)); provider analysis UI ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Business Context

This is the first customer-visible payoff of the foundation — the storefront experience that demonstrates confident, explainable shopping.

## Functional Requirements

- Capture attribute inputs from the shopper.
- Call the recommendation API and render ranked results.
- Clearly show rationale and avoid-tag warnings.

## Technical Requirements

- Storefront components + API client for `/beauty/recommendations/*`.
- Loading/empty/error states; accessible UI.
- Uses the API base URL configured in [Phase 0.4 — Frontend Build Baseline](../Phase%200%20%E2%80%94%20Baseline%20Verification/Phase%200%204%20%E2%80%94%20Frontend%20Build%20Baseline%20cb28881fd71d477cb2f5b21018644c2e.md).

## Architecture Impact

- First storefront consumer of the Beauty API; no backend changes.

## Dependencies

- [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md).

## Detailed Implementation Tasks

- [ ]  Build attribute input UI.
- [ ]  Implement API client for generate + fetch.
- [ ]  Render ranked results with rationale + warnings.
- [ ]  Handle loading/empty/error states.
- [ ]  Component/integration tests.

## Deliverables

- Storefront recommendation flow + components.
- Frontend tests.

## Testing & Validation Strategy

- Component tests for rendering states.
- Integration test against a mocked recommendation API.
- Manual end-to-end against the running backend.

## Acceptance Criteria

- Shoppers can submit attributes and see ranked recommendations with warnings.
- States are handled gracefully.

## Exit Criteria

- Storefront recommendation UI merged and demoable.

## Risks & Mitigations

- **API/UI contract drift** → typed API client validated against [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md).
- **Confusing rationale** → UX review of explanation copy.

## Rollout Plan

- Ship with Phase 1; featured in the Phase 6 demo.

## Success Metrics

- End-to-end recommendation flow works in the demo.
- 0 unhandled UI error states in testing.

## Related Documentation

- Parent: [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · Demo: [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md)

## Future Considerations

- Personalized, tenant-branded storefront in [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).
- Try-on integration in [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md).