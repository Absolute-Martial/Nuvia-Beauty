# Phase 1.1 — Private S3 Storage Config & Disks

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-1/phase-1.1-storage-config-disks.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) › **Phase 1.1**

## Overview

Configures the Laravel filesystem disks that back all beauty media, separating public assets from private beauty inputs, results, and calibration data. This sub-phase turns the verified storage baseline from [Phase 0.5 — Storage & Dependency Baseline](../Phase%200%20%E2%80%94%20Baseline%20Verification/Phase%200%205%20%E2%80%94%20Storage%20&%20Dependency%20Baseline%20e0aa2e342cde4095965aebef8fbf13c3.md) into application-level disk definitions and signed-URL policy.

## Objectives

- Define four S3 disks mapped to their buckets with correct visibility.
- Standardize signed-URL TTLs (PUT 15m, GET 60m).
- Provide a single, documented configuration surface in `config/filesystems.php` and `.env.example`.

## Scope

**In scope**

- Disks `s3_public`, `s3_beauty_inputs`, `s3_beauty_results`, `s3_beauty_calibration`.
- Buckets `nuvia-public-assets` (public), `nuvia-private-beauty-inputs`, `nuvia-private-beauty-results`, `nuvia-private-beauty-calibration` (private).
- Signed URL generation configuration.

**Out of scope**

- The media asset model ([Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)) and storage APIs ([Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)).

## Business Context

Customer beauty media is sensitive. Correct private/public separation and short-lived signed URLs are the foundation of the product's privacy and consent posture (tiers T0–T4) and a prerequisite for every downstream beauty feature.

## Functional Requirements

- Public assets are world-readable; beauty inputs/results/calibration are private.
- The application can issue time-limited signed PUT and GET URLs.
- Disk selection is explicit per use case.

## Technical Requirements

- `config/filesystems.php` disk entries for all four disks with endpoint, region, bucket, and visibility.
- `.env.example` keys for S3 endpoint, key/secret, region, and bucket names.
- Signed URL TTLs: PUT 15 minutes, GET 60 minutes.
- Compatible with MinIO/AIStor endpoint addressing.

## Architecture Impact

- Introduces the Storage domain's configuration contract. No DB schema yet. Establishes the disk abstraction other domains depend on.

## Dependencies

- [Phase 0.5 — Storage & Dependency Baseline](../Phase%200%20%E2%80%94%20Baseline%20Verification/Phase%200%205%20%E2%80%94%20Storage%20&%20Dependency%20Baseline%20e0aa2e342cde4095965aebef8fbf13c3.md).

## Detailed Implementation Tasks

- [ ]  Add four disks to `config/filesystems.php` with correct visibility.
- [ ]  Add S3 connection + bucket env keys to `.env.example`.
- [ ]  Implement/confirm signed PUT (15m) and GET (60m) URL generation.
- [ ]  Document endpoint addressing style for MinIO/AIStor.
- [ ]  Add config tests asserting disk visibility and TTLs.

## Deliverables

- `config/filesystems.php` disk definitions.
- Updated `.env.example`.
- Signed-URL configuration helper + tests.

## Testing & Validation Strategy

- Unit: config resolves four disks with expected visibility.
- Integration: signed PUT/GET round-trip within TTL; expired URL rejected.
- Negative: private object not publicly accessible.

## Acceptance Criteria

- Four disks resolve with correct visibility and buckets.
- Signed URLs honor configured TTLs.

## Exit Criteria

- Storage configuration merged and covered by tests; ready for the media model.

## Risks & Mitigations

- **Misclassified bucket visibility** → enforce via config tests and bucket policy review.
- **TTL drift** → centralize TTL constants.

## Rollout Plan

- Config-only change; deploy with the rest of Phase 1. No data migration.

## Success Metrics

- 0 public-exposure findings on private buckets.
- 100% signed-URL tests passing.

## Related Documentation

- Parent: [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · Architecture: [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21)

## Future Considerations

- Per-tenant bucket prefixes for the multi-tenant work in [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).
- Bucket lifecycle/retention automation.