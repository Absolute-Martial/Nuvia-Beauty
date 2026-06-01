# Phase 0.5 — Storage & Dependency Baseline

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-0/phase-0.5-storage-dependency-baseline.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) › **Phase 0.5**

## Overview

Confirms the S3-compatible object storage and its buckets/disks exist and are correctly classified public vs private, and that key backend dependencies resolve. This baseline is a direct prerequisite for the storage work in [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md).

## Objectives

- Verify buckets and disk mappings exist with correct visibility.
- Confirm signed-URL TTLs are configured.
- Confirm backend dependencies install/resolve.

## Scope

**In scope**

- Buckets: `nuvia-public-assets` (public), `nuvia-private-beauty-inputs`, `nuvia-private-beauty-results`, `nuvia-private-beauty-calibration` (private).
- Disks: `s3_public`, `s3_beauty_inputs`, `s3_beauty_results`, `s3_beauty_calibration`.
- Signed URL TTLs: PUT 15m, GET 60m.

**Out of scope**

- Media asset model and upload APIs ([Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)).

## Business Context

Private beauty media must never be publicly accessible; verifying bucket visibility early protects customer privacy and the compliance posture the whole product depends on.

## Functional Requirements

- Public bucket is readable; private buckets are not publicly readable.
- Disks map to the correct buckets.
- Signed URLs generate with the configured TTLs.

## Technical Requirements

- S3 endpoint (MinIO/AIStor) reachable at 9000/9001.
- Disk config in `config/filesystems.php` matches bucket names.
- Credentials scoped to required buckets.

## Architecture Impact

- Confirms the storage substrate for the Storage domain; no schema changes yet.

## Dependencies

- [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) (S3 reachable).

## Detailed Implementation Tasks

- [ ]  Confirm all four buckets exist.
- [ ]  Verify public bucket is publicly readable; private buckets are not.
- [ ]  Verify disk-to-bucket mapping in `config/filesystems.php`.
- [ ]  Generate a test signed PUT (15m) and GET (60m) and confirm expiry behavior.
- [ ]  Run backend dependency install and confirm resolution.

## Deliverables

- Bucket/disk visibility verification note.
- Signed-URL TTL confirmation.

## Testing & Validation Strategy

- Positive: signed PUT/GET within TTL works.
- Negative: private object is not accessible without a signed URL; expired URL is rejected.

## Acceptance Criteria

- Buckets/disks exist and are correctly classified.
- Signed URL TTLs behave as configured.

## Exit Criteria

- Storage and dependency baseline recorded; ready for Phase 1.

## Risks & Mitigations

- **Private bucket publicly readable** → fix bucket policy before proceeding (privacy-critical).
- **Endpoint style mismatch** → document path vs virtual-hosted addressing.

## Rollout Plan

- Local + shared object store. No production impact.

## Success Metrics

- 4/4 buckets verified with correct visibility.
- Negative-access test passes (no public access to private objects).

## Related Documentation

- Parent: [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Next: [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)

## Future Considerations

- Automate bucket/policy provisioning as code (IaC).
- Add lifecycle rules for input/result retention.