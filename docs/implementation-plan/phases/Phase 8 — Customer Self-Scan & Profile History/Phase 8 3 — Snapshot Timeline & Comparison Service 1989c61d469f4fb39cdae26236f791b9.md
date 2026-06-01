# Phase 8.3 — Snapshot Timeline & Comparison Services

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-8/phase-8.3-snapshot-timeline-comparison-services.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) › **Phase 8.3**

## Overview

Provides services to build a chronological timeline of profile snapshots and to compare snapshots over time, enabling customers to track beauty changes and progress.

## Objectives

- Build a chronological snapshot timeline.
- Compare two snapshots and surface deltas.
- Power the profile-history UI.

## Scope

**In scope**

- `ProfileSnapshotTimelineService`, `ProfileSnapshotComparisonService`.

**Out of scope**

- History UI ([Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)).

## Business Context

Progress tracking over time is a key retention driver — customers see the value of recommendations and analysis evolving.

## Functional Requirements

- Return ordered snapshot timeline per profile.
- Compute deltas between two snapshots.

## Technical Requirements

- Timeline service over `beauty_profile_snapshots`.
- Comparison service computing attribute deltas.

## Architecture Impact

- Adds read/derivation services over the snapshot model.

## Dependencies

- [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md), [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) (snapshots).

## Detailed Implementation Tasks

- [ ]  Implement `ProfileSnapshotTimelineService`.
- [ ]  Implement `ProfileSnapshotComparisonService` (deltas).
- [ ]  Optimize queries + pagination.
- [ ]  Unit tests (ordering, deltas, edge cases).

## Deliverables

- Timeline + comparison services + tests.

## Testing & Validation Strategy

- Tests for ordering, delta correctness, empty/single-snapshot cases.

## Acceptance Criteria

- Timeline and comparisons return correct, performant results.

## Exit Criteria

- Services merged and ready for APIs/UI.

## Risks & Mitigations

- **Slow timelines at scale** → indexing + pagination.

## Rollout Plan

- Backend-only first; surfaced via APIs/UI later.

## Success Metrics

- Correct deltas; timeline latency within budget.

## Related Documentation

- Parent: [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md)

## Future Considerations

- Trend analytics + progress insights.