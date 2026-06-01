# Phase 7.5 — Frontend (PWA, Vendor, Admin)

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-7/phase-7.5-frontend-pwa-vendor-admin.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) › **Phase 7.5**

## Overview

Delivers the tenant-aware frontends — customer PWA, vendor portal, and admin panel — that render per-shop branding, consume tenant context, and surface signed customer profile access.

## Objectives

- Make frontends tenant- and branding-aware.
- Render the customer profile via signed links.
- Support vendor/admin domain + branding management.

## Scope

**In scope**

- Storefront/PWA, vendor portal, admin panel changes for tenancy + branding + signed profile views.

**Out of scope**

- Backend tenancy ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)); DNS/TLS ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)).

## Business Context

The frontends are where personalization becomes visible; consistent branding drives trust and conversion.

## Functional Requirements

- Apply per-shop branding from settings.
- Render customer profile via signed link.
- Vendor/admin manage domains + branding.

## Technical Requirements

- Tenant-aware data fetching + branding theming (Next.js apps).
- Signed-link profile view; graceful expiry handling.

## Architecture Impact

- Frontends become tenant consumers; no new backend contracts beyond prior sub-phases.

## Dependencies

- [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md), [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).

## Detailed Implementation Tasks

- [ ]  Apply per-shop branding/theming in storefront + PWA.
- [ ]  Build the signed-link customer profile view.
- [ ]  Add vendor/admin domain + branding management UI.
- [ ]  Handle expired/invalid links gracefully.
- [ ]  Component + integration tests.

## Deliverables

- Tenant-aware frontends + profile view + management UI + tests.

## Testing & Validation Strategy

- Tests for branding application, signed-link views, expiry handling.

## Acceptance Criteria

- Frontends render correct per-tenant branding and signed profile views.

## Exit Criteria

- Frontends merged and tenant-aware.

## Risks & Mitigations

- **Branding bleed across tenants** → strict tenant-scoped fetching.

## Rollout Plan

- Rolled out with backend tenancy; staging-validated.

## Success Metrics

- Correct branding per tenant; low UI error rate.

## Related Documentation

- Parent: [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Settings: [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) · Signed links: [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)

## Future Considerations

- Per-tenant theming editor + preview.