# Phase 2.4 — Vendor Inline Mapping Editor (Owner-Scoped)

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-2/phase-2.4-vendor-inline-mapping-editor.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) › **Phase 2.4**

## Overview

Provides a vendor-portal mapping editor scoped to the vendor's own products, so sellers can curate mappings for their catalog while strict ownership boundaries prevent cross-vendor access.

## Objectives

- Let vendors edit mappings for products they own.
- Enforce owner-scoped authorization.
- Reuse mapping validation and APIs.

## Scope

**In scope**

- Vendor-portal (port 3004) owner-scoped mapping editor.
- Owner-scoped authorization on mapping endpoints.

**Out of scope**

- Platform-wide admin editing ([Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md)).

## Business Context

Vendors know their products best; enabling owner-scoped curation improves mapping coverage and quality while preserving tenant isolation.

## Functional Requirements

- Vendors see and edit only their products' mappings.
- Edits validated and persisted via mapping APIs.
- Cross-vendor access is denied.

## Technical Requirements

- Vendor-portal components + API client.
- Server-side owner-scoping/authorization policy on mapping endpoints.
- Client validation mirroring vocabularies.

## Architecture Impact

- Strengthens authorization on mapping APIs (owner scoping), foundational for tenancy in [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).

## Dependencies

- [Phase 1.4 — Product Mapping Model & APIs](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%204%20%E2%80%94%20Product%20Mapping%20Model%20&%20APIs%20b93ddefb67034215bd20b4fbcdae29b8.md), [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) (shared editor patterns).

## Detailed Implementation Tasks

- [ ]  Add owner-scoped authorization to mapping endpoints.
- [ ]  Build vendor-portal mapping editor (own products only).
- [ ]  Implement API client + validation.
- [ ]  AuthZ tests for cross-vendor denial.
- [ ]  Component + integration tests.

## Deliverables

- Vendor mapping editor UI + owner-scoped authorization + tests.

## Testing & Validation Strategy

- AuthZ: vendor cannot access others' mappings.
- Component/integration for edit/validation.

## Acceptance Criteria

- Vendors edit only their mappings; cross-vendor access denied.

## Exit Criteria

- Vendor editor + owner scoping merged and tested.

## Risks & Mitigations

- **Authorization leakage** → server-enforced owner scoping + tests.
- **Inconsistent UX with admin editor** → share components/patterns.

## Rollout Plan

- Ship with Phase 2; vendor-scoped.

## Success Metrics

- 0 cross-vendor access in testing; improved mapping coverage by vendors.

## Related Documentation

- Parent: [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Tenancy: [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) · API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md)

## Future Considerations

- Full multi-tenant authorization model in [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).
- Vendor change approval workflows.