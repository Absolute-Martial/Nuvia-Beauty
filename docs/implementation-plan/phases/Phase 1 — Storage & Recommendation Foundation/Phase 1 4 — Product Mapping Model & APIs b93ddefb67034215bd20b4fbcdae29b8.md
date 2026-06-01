# Phase 1.4 — Product Mapping Model & APIs

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-1/phase-1.4-product-mapping-model-apis.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) › **Phase 1.4**

## Overview

Introduces beauty product mappings — the structured attributes (skin types, concerns, tones, undertones, ingredients, and avoid-tags) that connect catalog products to the recommendation engine. Includes the model, seeding, and CRUD APIs.

## Objectives

- Model product-to-beauty-attribute mappings.
- Provide CRUD APIs for mappings.
- Seed initial mappings for development and demos.

## Scope

**In scope**

- Migration `000002_create_product_mappings`.
- `app/Domains/Beauty` mapping model + service.
- `GET/POST /beauty/product-mappings`, `PUT /beauty/product-mappings/{id}`.
- `BeautyProductMappingSeeder`.

**Out of scope**

- Recommendation generation ([Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)) and inline editors ([Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md)).

## Business Context

Mappings are the knowledge base that powers confident recommendations; their quality directly drives recommendation relevance and the demo narrative.

## Functional Requirements

- Create, read, update product mappings.
- Represent skin types, concerns, tones, undertones, ingredients, and avoid-tags.
- Seed representative mappings.

## Technical Requirements

- Table `product_mappings` keyed to catalog products.
- Attribute taxonomy: skin (oily/dry/combination/sensitive); concerns (acne/dark_spots/hydration/dullness); tones (fair/medium/deep); undertones (warm/cool/neutral); ingredients (niacinamide/hyaluronic_acid/salicylic_acid); avoid (fragrance/alcohol/essential_oil).
- Validation against the controlled vocabularies.

## Architecture Impact

- Adds the Beauty domain's core knowledge table consumed by the recommendation engine.

## Dependencies

- [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) (domain scaffolding), catalog products available.

## Detailed Implementation Tasks

- [ ]  Write migration `000002_create_product_mappings`.
- [ ]  Implement mapping model + service with vocabulary validation.
- [ ]  Implement `GET/POST` and `PUT` endpoints.
- [ ]  Implement `BeautyProductMappingSeeder`.
- [ ]  Tests for CRUD + validation.

## Deliverables

- `product_mappings` migration + model.
- Mapping CRUD endpoints + seeder.

## Testing & Validation Strategy

- Unit: vocabulary validation.
- Feature: CRUD endpoints with authZ.
- Seed verification.

## Acceptance Criteria

- Mappings can be created/edited via API with validated attributes.
- Seeder produces representative data.

## Exit Criteria

- Mapping model + APIs + seeder merged and tested.

## Risks & Mitigations

- **Inconsistent taxonomy** → enforce controlled vocabularies in validation.
- **Sparse mappings** → seeder + Phase 2 editors to improve coverage.

## Rollout Plan

- Ship with Phase 1 migrations; seed in non-prod first.

## Success Metrics

- Mapping coverage for all seeded demo products.
- 100% validation test pass rate.

## Related Documentation

- Parent: [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Editors: [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) · Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md)

## Future Considerations

- Signal-driven mapping refinement in [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md).
- Vendor-owned mappings under tenancy in [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).