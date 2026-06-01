# Phase 1.2 — Media Asset Model & Lifecycle

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-1/phase-1.2-media-asset-model-lifecycle.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) › **Phase 1.2**

## Overview

Defines the `media_assets` data model and the lifecycle that tracks an uploaded object from a reserved upload slot through confirmation, availability, and deletion. This is the system of record that links storage objects to domain entities.

## Objectives

- Persist media metadata (disk, key, mime, size, status, owner).
- Model the lifecycle states: reserved → confirmed → available → deleted.
- Enforce integrity between DB records and stored objects.

## Scope

**In scope**

- Migration `000001_create_media_assets`.
- `app/Domains/Storage` model + repository/service for media assets.
- Lifecycle state transitions and validation.

**Out of scope**

- Endpoint surface ([Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)).

## Business Context

A reliable media record is required for privacy controls, retention, auditability, and for associating media with consultations and customer profiles in later phases.

## Functional Requirements

- Create a reserved media record when an upload slot is issued.
- Mark a record confirmed once the client completes upload.
- Support soft delete / hard delete with object cleanup.

## Technical Requirements

- Table `media_assets` with columns for disk, bucket key, mime type, size, checksum, status, owner, timestamps.
- Status enum with guarded transitions.
- Indexes on owner and status; unique on (disk, key).

## Architecture Impact

- Adds the first Storage-domain table and the canonical media abstraction consumed by Beauty domain features.

## Dependencies

- [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md).

## Detailed Implementation Tasks

- [ ]  Write migration `000001_create_media_assets`.
- [ ]  Implement the MediaAsset model and repository.
- [ ]  Implement lifecycle service with guarded transitions.
- [ ]  Add validation for mime/size limits per disk.
- [ ]  Unit tests for transitions and integrity rules.

## Deliverables

- `media_assets` migration + model.
- Lifecycle service with tests.

## Testing & Validation Strategy

- Unit: each state transition (valid and invalid).
- Integration: reserve → confirm → delete with object presence checks.

## Acceptance Criteria

- Media records persist with correct status transitions.
- Invalid transitions are rejected.

## Exit Criteria

- Media model + lifecycle merged and tested; ready for the storage APIs.

## Risks & Mitigations

- **Orphaned objects/records** → reconciliation job + unique (disk, key) constraint.
- **Unbounded uploads** → enforce size/mime limits.

## Rollout Plan

- Ship with Phase 1 migration set; backward compatible (new table).

## Success Metrics

- 0 orphaned media after reconciliation.
- 100% lifecycle test coverage on transitions.

## Related Documentation

- Parent: [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · Queue/jobs: [[queue-jobs.md](http://queue-jobs.md)](../../../backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md)

## Future Considerations

- Retention policies and media privacy controls expand in [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md).
- Result media for VTO in [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md).