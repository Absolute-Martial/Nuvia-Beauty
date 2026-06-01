# Phase 5.3 — Result Normalization, Storage & Recompute

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-5/phase-5.3-result-normalization-storage-recompute.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) › **Phase 5.3**

## Overview

Normalizes provider analysis output into the internal analysis-result schema, stores it, and recomputes recommendations using the new objective signals (e.g. skin tone/undertone).

## Objectives

- Normalize provider results to the internal model.
- Persist analysis results to the consultation schema.
- Recompute recommendations incorporating analysis.

## Scope

**In scope**

- `NormalizePerfectCorpResultService`; persistence to `beauty_analysis_results`.
- Recommendation recompute using analysis-derived attributes.

**Out of scope**

- Demo fallback shaping ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Business Context

Objective analysis improves recommendation accuracy and customer trust; normalization keeps the platform provider-agnostic.

## Functional Requirements

- Map provider fields to internal attributes (skin tone, undertone, concerns).
- Persist results; transition session to `analysis_completed`.
- Recompute and return enriched recommendations.

## Technical Requirements

- Normalization service with explicit field mapping + validation.
- Persist to results table; link to session/profile.
- Recompute hook into the recommendation engine.

## Architecture Impact

- Connects provider output to the recommendation engine via a normalized contract.

## Dependencies

- [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md), [Phase 1.5 — Recommendation Engine](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%205%20%E2%80%94%20Recommendation%20Engine%20edbdb128ef9442a99dce738719dc2a00.md).

## Detailed Implementation Tasks

- [ ]  Implement `NormalizePerfectCorpResultService` + field mapping.
- [ ]  Persist normalized results to `beauty_analysis_results`.
- [ ]  Transition session to `analysis_completed`.
- [ ]  Trigger recommendation recompute with analysis attributes.
- [ ]  Unit + integration tests with sample payloads.

## Deliverables

- Normalization service + persistence + recompute integration + tests.

## Testing & Validation Strategy

- Unit: mapping correctness for AI Skin Analysis + Skin Tone fallback.
- Integration: end-to-end task→result→recompute.

## Acceptance Criteria

- Provider results normalize, persist, and enrich recommendations.

## Exit Criteria

- Result handling merged and tested.

## Risks & Mitigations

- **Provider schema changes** → tolerant mapping + versioning.
- **Bad data into recs** → validate before recompute.

## Rollout Plan

- Works in demo mode with synthetic results; live behind gate.

## Success Metrics

- Normalization success rate; enriched recs generated.

## Related Documentation

- Parent: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Engine: [Phase 1.5 — Recommendation Engine](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation/Phase%201%205%20%E2%80%94%20Recommendation%20Engine%20edbdb128ef9442a99dce738719dc2a00.md) · Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md)

## Future Considerations

- Additional provider metrics feeding scoring weights.