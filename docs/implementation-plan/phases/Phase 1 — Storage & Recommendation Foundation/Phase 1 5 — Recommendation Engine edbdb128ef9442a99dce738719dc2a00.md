# Phase 1.5 — Recommendation Engine

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-1/phase-1.5-recommendation-engine.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) › **Phase 1.5**

## Overview

Implements the rule-based recommendation engine that scores and ranks products against a customer's beauty attributes using the mappings from [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md), including avoid-tag warnings. Generates and persists recommendation results.

## Objectives

- Score products against requested attributes.
- Produce ranked recommendations with rationale and avoid-tag warnings.
- Persist recommendation results for retrieval.

## Scope

**In scope**

- Migration `000003_create_recommendations`.
- Recommendation service in `app/Domains/Beauty`.
- `POST /beauty/recommendations/generate`, `GET /beauty/recommendations/{id}`.

**Out of scope**

- Storefront UI ([Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)); provider-driven analysis ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Business Context

The recommendation engine is the heart of the “confidence shopping” value proposition — it turns attributes into trustworthy, explainable product suggestions.

## Functional Requirements

- Accept attribute inputs and return ranked products with scores and reasons.
- Flag products carrying avoid-tags relative to the request.
- Persist and retrieve generated recommendations.

## Technical Requirements

- Table `recommendations` storing inputs, ranked outputs, and rationale.
- Deterministic, explainable scoring algorithm over the mapping taxonomy.
- API endpoints for generate + fetch.

## Architecture Impact

- Adds the scoring service and result store that later provider results (Phase 5) and consultations (Phase 4) feed into.

## Dependencies

- [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md).

## Detailed Implementation Tasks

- [ ]  Write migration `000003_create_recommendations`.
- [ ]  Implement scoring service (match scoring + avoid-tag penalties).
- [ ]  Implement generate + fetch endpoints.
- [ ]  Persist rationale for explainability.
- [ ]  Unit tests for scoring; feature tests for endpoints.

## Deliverables

- `recommendations` migration + model.
- Scoring service + endpoints with tests.

## Testing & Validation Strategy

- Unit: scoring determinism and avoid-tag handling.
- Feature: generate → fetch round-trip.
- Fixture-based ranking assertions.

## Acceptance Criteria

- Generates ranked, explainable recommendations with avoid-tag warnings.
- Results persist and are retrievable by id.

## Exit Criteria

- Engine + APIs merged and tested with representative fixtures.

## Risks & Mitigations

- **Opaque ranking** → persist rationale; keep scoring deterministic.
- **Poor relevance from sparse mappings** → depend on Phase 1.4 seeding + Phase 2 editors.

## Rollout Plan

- Ship with Phase 1; validated against seeded demo data in Phase 6.

## Success Metrics

- Deterministic, reproducible rankings on fixtures.
- Avoid-tag warnings correctly surfaced 100% of the time in tests.

## Related Documentation

- Parent: [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Signals: [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md) · Provider analysis: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)

## Future Considerations

- Signal-weighted scoring in [Phase 2 — Mapping Editors & Event Signals](../Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md).
- Custom analysis model beyond the numbered roadmap.