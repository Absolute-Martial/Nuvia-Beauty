# Phase 1.3 — Storage APIs

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-1/phase-1.3-storage-apis.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) › **Phase 1.3**

## Overview

Exposes the HTTP API for direct-to-storage uploads using signed URLs, plus confirmation, download-URL issuance, and deletion. Clients never proxy bytes through the API — they upload directly to object storage using short-lived signed URLs.

## Objectives

- Provide upload-slot issuance, confirmation, download, and delete endpoints.
- Keep the API stateless with respect to file bytes.
- Enforce ownership and authorization on every operation.

## Scope

**In scope**

- `POST /api/v1/storage/upload-slots`
- `POST /api/v1/storage/media/{id}/confirm`
- `GET /api/v1/storage/media/{id}/download-url`
- `DELETE /api/v1/storage/media/{id}`
- Routes in `routes/api/v1/storage.php`.

**Out of scope**

- Product mapping and recommendation endpoints ([Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)/[Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md)).

## Business Context

Direct signed-URL uploads keep the API lightweight and secure while giving every frontend a uniform way to store and retrieve media.

## Functional Requirements

- Issue a signed PUT slot and create a reserved media record.
- Confirm an upload and transition the record to available.
- Issue a signed GET download URL for authorized requesters.
- Delete media (record + object).

## Technical Requirements

- Controllers in `app/Domains/Storage`; routes in `routes/api/v1/storage.php` included from `routes/api.php`.
- Request validation; authorization policies on ownership.
- Signed URL TTLs from Phase 1.1 (PUT 15m, GET 60m).

## Architecture Impact

- Establishes the Storage API contract that the Beauty domain and all frontends consume.

## Dependencies

- [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md).

## Detailed Implementation Tasks

- [ ]  Implement upload-slot controller (returns signed PUT + media id).
- [ ]  Implement confirm endpoint (verifies object, sets status).
- [ ]  Implement download-url endpoint (signed GET).
- [ ]  Implement delete endpoint (object + record).
- [ ]  Register routes; add authorization policies.
- [ ]  Feature tests for each endpoint (happy + auth-failure paths).

## Deliverables

- Four storage endpoints + routes.
- Feature tests and API contract entry.

## Testing & Validation Strategy

- Feature tests per endpoint.
- AuthZ tests: non-owner cannot confirm/download/delete.
- Contract check against [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md).

## Acceptance Criteria

- All four endpoints function and enforce ownership.
- Upload → confirm → download → delete round-trip works.

## Exit Criteria

- Storage API merged, documented, and tested.

## Risks & Mitigations

- **Unauthorized access to media** → ownership policies + signed URLs only.
- **Confirmation without a real object** → verify object existence on confirm.

## Rollout Plan

- Ship with Phase 1; additive routes, no breaking changes.

## Success Metrics

- 100% endpoint test pass rate.
- 0 authorization escapes in testing.

## Related Documentation

- Parent: [Phase 1 — Storage & Recommendation Foundation](../Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · Testing: [testing/](../../../testing%20fb05a1e82065477d8aaead149521944f.md)

## Future Considerations

- Customer-facing scan uploads reuse this API in [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md).
- Multipart/large-file support if needed.