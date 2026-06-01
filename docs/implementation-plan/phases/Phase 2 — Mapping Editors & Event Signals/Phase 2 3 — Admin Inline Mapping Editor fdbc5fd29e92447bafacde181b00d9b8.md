# Phase 2.3 — Admin Inline Mapping Editor

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-2/phase-2.3-admin-inline-mapping-editor.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) › **Phase 2.3**

## Overview

Delivers an admin-panel UI for inline editing of beauty product mappings, so platform admins can curate the attribute taxonomy that powers recommendations without engineering involvement.

## Objectives

- Provide an inline mapping editor in the admin panel.
- Validate edits against the controlled vocabularies.
- Reflect changes immediately for recommendation inputs.

## Scope

**In scope**

- Admin-panel (port 3002) mapping editor UI.
- Integration with `GET/POST/PUT /beauty/product-mappings`.

**Out of scope**

- Vendor (owner-scoped) editor ([Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md)).

## Business Context

Admins must be able to maintain mapping quality at scale; an inline editor reduces turnaround and keeps recommendations relevant.

## Functional Requirements

- List, search, and edit product mappings inline.
- Validate attributes against vocabularies.
- Persist via the mapping APIs with optimistic UI feedback.

## Technical Requirements

- Admin-panel components + typed API client for mapping endpoints.
- Client-side validation mirroring server vocabularies.
- Authorization: platform-admin scope.

## Architecture Impact

- New admin consumer of the mapping APIs; no backend schema change.

## Dependencies

- [Phase 1.4 — Product Mapping Model & APIs](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%204%20%E2%80%94%20Product%20Mapping%20Model%20&%20APIs%20b93ddefb67034215bd20b4fbcdae29b8.md).

## Detailed Implementation Tasks

- [ ]  Build mapping list + inline edit UI in admin-panel.
- [ ]  Implement typed API client + validation.
- [ ]  Handle save/error/optimistic states.
- [ ]  Add admin authorization gating.
- [ ]  Component + integration tests.

## Deliverables

- Admin mapping editor UI + tests.

## Testing & Validation Strategy

- Component tests for edit/validation states.
- Integration against mapping API (mocked + live).
- AuthZ test: only admins can edit.

## Acceptance Criteria

- Admins can edit mappings inline with validation and persistence.

## Exit Criteria

- Admin editor merged and demoable.

## Risks & Mitigations

- **Invalid edits** → mirror server validation client-side.
- **Concurrent edits** → last-write-wins with change indicators (revisit if needed).

## Rollout Plan

- Ship with Phase 2; admin-only.

## Success Metrics

- Reduced mapping edit turnaround; 0 invalid mappings saved.

## Related Documentation

- Parent: [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · Mappings: [Phase 1.4 — Product Mapping Model & APIs](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%204%20%E2%80%94%20Product%20Mapping%20Model%20&%20APIs%20b93ddefb67034215bd20b4fbcdae29b8.md)

## Future Considerations

- Bulk editing and import/export.
- Audit trail for mapping changes.