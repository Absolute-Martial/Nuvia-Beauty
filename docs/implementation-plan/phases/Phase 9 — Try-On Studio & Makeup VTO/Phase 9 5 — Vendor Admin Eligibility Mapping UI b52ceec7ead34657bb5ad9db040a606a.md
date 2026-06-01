# Phase 9.5 — Vendor/Admin Eligibility Mapping UI

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-9/phase-9.5-vendor-admin-eligibility-mapping-ui.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) › **Phase 9.5**

## Overview

Provides vendor/admin tools to manage which products are eligible for virtual try-on and to configure VTO mapping — giving operators control over the try-on catalog.

## Objectives

- Let vendors/admins manage VTO eligibility.
- Configure product→VTO mappings.
- Keep eligibility data accurate + auditable.

## Scope

**In scope**

- Vendor/admin eligibility + mapping management UI.

**Out of scope**

- Customer try-on UI ([Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)).

## Business Context

Operators need control over which products support try-on to ensure quality and accuracy of the experience.

## Functional Requirements

- View/manage product VTO eligibility.
- Configure + validate mappings.

## Technical Requirements

- UI over the eligibility service + mapping APIs.
- Tenant-scoped, validated edits.

## Architecture Impact

- Management surface over VTO eligibility data.

## Dependencies

- [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md), [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md).

## Detailed Implementation Tasks

- [ ]  Build the eligibility management UI.
- [ ]  Configure + validate VTO mappings.
- [ ]  Enforce tenant scope + audit edits.
- [ ]  Component + integration tests.

## Deliverables

- Eligibility/mapping management UI + tests.

## Testing & Validation Strategy

- Tests for eligibility edits, validation, tenant scoping.

## Acceptance Criteria

- Operators manage VTO eligibility accurately + safely.

## Exit Criteria

- Management UI merged; Phase 9 complete.

## Risks & Mitigations

- **Misconfigured eligibility** → validation + previews + audit.

## Rollout Plan

- Behind feature flag with the try-on rollout.

## Success Metrics

- Accurate eligibility config; low misconfiguration rate.

## Related Documentation

- Parent: [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Eligibility: [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) · Mapping editors: [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md)

## Future Considerations

- **Beyond roadmap:** manufacturer-managed eligibility via a brand portal + feedback loop.