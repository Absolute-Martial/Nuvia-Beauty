# Phase 7.1 — Domain & Subdomain Strategy

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-7/phase-7.1-domain-subdomain-strategy.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) › **Phase 7.1**

## Overview

Defines the domain and subdomain strategy for multi-tenant beauty experiences: the canonical apex/beauty domain, per-shop subdomains, and the API host, plus how tenants map to domains.

## Objectives

- Establish the canonical domain scheme.
- Define per-shop subdomain conventions.
- Specify the tenant→domain mapping model.

## Scope

**In scope**

- Domains: `beauty.nuvia.example`, `shop-slug.beauty.nuvia.example`, `api.nuvia.example`.
- `shop_beauty_domains` table modeling tenant→domain mapping.

**Out of scope**

- Runtime resolution middleware ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)); DNS/TLS provisioning ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)).

## Business Context

Personalized per-shop domains build vendor brand trust and enable shareable, branded customer experiences.

## Functional Requirements

- Each shop maps to a unique subdomain.
- Reserved/blocked slugs are enforced.

## Technical Requirements

- `shop_beauty_domains` schema (shop, host, status, verification).
- Slug normalization + uniqueness + reserved list.

## Architecture Impact

- Introduces a tenant→domain mapping foundation for multi-tenant routing.

## Dependencies

- [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md).

## Detailed Implementation Tasks

- [ ]  Define the domain/subdomain scheme + reserved slugs.
- [ ]  Create the `shop_beauty_domains` table + model.
- [ ]  Implement slug normalization + uniqueness.
- [ ]  Document the mapping model.

## Deliverables

- Domain strategy doc + `shop_beauty_domains` schema/model.

## Testing & Validation Strategy

- Unit tests for slug rules, uniqueness, reserved-slug rejection.

## Acceptance Criteria

- Shops map deterministically to valid, unique subdomains.

## Exit Criteria

- Mapping foundation ready for resolution middleware.

## Risks & Mitigations

- **Slug collisions/abuse** → reserved list + validation.

## Rollout Plan

- Schema first; no public domains until DNS/TLS phase.

## Success Metrics

- 0 invalid/colliding slugs; clean mapping model.

## Related Documentation

- Parent: [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Architecture: [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21) · Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md)

## Future Considerations

- Custom vanity domains per shop.