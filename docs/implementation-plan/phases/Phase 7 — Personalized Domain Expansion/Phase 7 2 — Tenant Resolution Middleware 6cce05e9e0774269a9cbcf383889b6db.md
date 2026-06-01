# Phase 7.2 — Tenant Resolution Middleware

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-7/phase-7.2-tenant-resolution-middleware.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) › **Phase 7.2**

## Overview

Implements request-time tenant resolution: mapping the incoming host to a shop, establishing a tenant context, and scoping subsequent queries and policies to that tenant.

## Objectives

- Resolve tenant from host on every request.
- Provide a shared tenant context.
- Fail safely for unknown/unverified hosts.

## Scope

**In scope**

- `ShopDomainResolver`, `BeautyTenantContext`, `TenantResolved` middleware.

**Out of scope**

- Domain modeling ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)); branding ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)).

## Business Context

Reliable tenant isolation is the backbone of multi-tenant safety and per-shop personalization.

## Functional Requirements

- Map host → shop; set tenant context.
- Reject/redirect unknown or unverified hosts.

## Technical Requirements

- Resolver querying `shop_beauty_domains`.
- Middleware setting `BeautyTenantContext`; request-scoped lifecycle.

## Architecture Impact

- Adds a tenancy layer that all beauty requests pass through.

## Dependencies

- [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).

## Detailed Implementation Tasks

- [ ]  Implement `ShopDomainResolver`.
- [ ]  Implement `BeautyTenantContext` (request-scoped).
- [ ]  Implement `TenantResolved` middleware + host validation.
- [ ]  Handle unknown/unverified hosts safely.
- [ ]  Unit + feature tests for resolution + isolation.

## Deliverables

- Resolver + context + middleware + tests.

## Testing & Validation Strategy

- Tests for valid host, unknown host, and tenant isolation.

## Acceptance Criteria

- Every request resolves to the correct tenant or is safely rejected.

## Exit Criteria

- Tenant resolution merged and isolating correctly.

## Risks & Mitigations

- **Cross-tenant leakage** → enforced context + scoped queries + tests.

## Rollout Plan

- Enabled with domain rollout; staging-validated first.

## Success Metrics

- 0 cross-tenant leaks; correct resolution rate.

## Related Documentation

- Parent: [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Architecture: [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21)

## Future Considerations

- Per-tenant rate limits + caching of resolution.