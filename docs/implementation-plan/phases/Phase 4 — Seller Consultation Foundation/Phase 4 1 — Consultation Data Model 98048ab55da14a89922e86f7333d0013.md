# Phase 4.1 — Consultation Data Model

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-4/phase-4.1-consultation-data-model.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) › **Phase 4.1**

## Overview

Establishes the persistent data model for seller-led beauty consultations: sessions, customer profiles, profile snapshots, AI tasks, analysis results, quota accounting, and audit logs. This is the schema backbone for the consultation flow.

## Objectives

- Define migrations and models for the consultation domain.
- Capture relationships between sessions, profiles, snapshots, and tasks.
- Provide quota and audit primitives.

## Scope

**In scope**

- Tables: `beauty_sessions`, `beauty_profiles`, `beauty_profile_snapshots`, `beauty_ai_tasks`, `beauty_analysis_results`, `beauty_quota_accounts`, `beauty_quota_events`, `beauty_audit_logs`.
- Eloquent models + relationships.

**Out of scope**

- State transitions ([Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)) and APIs ([Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)).

## Business Context

Consultations are the core seller workflow; a well-formed data model enables sessions, history, recommendations, and the later provider-analysis and self-scan capabilities.

## Functional Requirements

- Persist sessions linked to vendor, customer profile, and media.
- Persist analysis tasks/results and quota usage.
- Record auditable events.

## Technical Requirements

- Migrations with correct keys, indexes, and constraints.
- Models with relationships (session→profile→snapshots; session→tasks→results).
- Quota accounts + events; append-only audit logs.

## Architecture Impact

- Introduces the consultation domain schema; foundation for [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md), [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md), and [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md).

## Dependencies

- [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) (Beauty domain + storage), [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md) (stable migrations).

## Detailed Implementation Tasks

- [ ]  Write migrations for all eight consultation tables.
- [ ]  Implement models + relationships.
- [ ]  Add indexes/constraints for query patterns.
- [ ]  Seed minimal fixtures for tests.
- [ ]  Unit tests for model relationships.

## Deliverables

- Consultation migrations + models + relationship tests.

## Testing & Validation Strategy

- `migrate:fresh` clean run.
- Unit tests for relationships and constraints.

## Acceptance Criteria

- All consultation tables migrate cleanly; relationships verified.

## Exit Criteria

- Data model merged and ready for the state machine.

## Risks & Mitigations

- **Schema rework later** → align with provider-analysis + self-scan needs now.
- **PII in schema** → minimize; align with consent tiers.

## Rollout Plan

- Additive migrations; ship with Phase 4.

## Success Metrics

- 0 migration failures; relationship tests pass.

## Related Documentation

- Parent: [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · Architecture: [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21)

## Future Considerations

- Partitioning/retention for high-volume sessions and audit logs.