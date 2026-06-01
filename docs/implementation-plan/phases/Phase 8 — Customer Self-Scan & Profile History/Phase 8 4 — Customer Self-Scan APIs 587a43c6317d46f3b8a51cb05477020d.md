# Phase 8.4 — Customer Self-Scan APIs

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-8/phase-8.4-customer-self-scan-apis.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) › **Phase 8.4**

## Overview

Exposes the HTTP API surface for customer self-scan: creating scans, attaching media, starting analysis, polling status, retrieving snapshots/comparisons, and requesting profile deletion.

## Objectives

- Provide a complete, documented self-scan API.
- Enforce access/quota/consent at the edge.
- Support profile history + deletion requests.

## Scope

**In scope**

- `POST /beauty/customer/scans` (+ `attach-media`, `analysis-start`, `status`); `GET` snapshots/compare; `POST /beauty/customer/profile/delete-request`.

**Out of scope**

- PWA UI ([Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)).

## Business Context

A clean API surface enables the customer PWA and any future clients to deliver self-scan + history features consistently.

## Functional Requirements

- Endpoints for scan lifecycle, media, analysis, status, history, deletion.
- Consistent auth, validation, and error contracts.

## Technical Requirements

- Routes + request/response contracts; policy enforcement.
- Align with `api-contracts` conventions.

## Architecture Impact

- Public customer API surface over the self-scan backend + services.

## Dependencies

- [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md), [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md), [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md).

## Detailed Implementation Tasks

- [ ]  Implement scan create + `attach-media` + `analysis-start` + `status`.
- [ ]  Implement snapshots/compare GET endpoints.
- [ ]  Implement `profile/delete-request` (via `ProfileDeleteRequestService`).
- [ ]  Document contracts in `api-contracts`.
- [ ]  Feature tests for each endpoint.

## Deliverables

- Self-scan API endpoints + contracts + tests.

## Testing & Validation Strategy

- Feature tests for happy paths, auth/quota denial, deletion requests.

## Acceptance Criteria

- All endpoints function with enforced policies + documented contracts.

## Exit Criteria

- APIs merged and documented.

## Risks & Mitigations

- **Contract drift** → documented contracts + tests.

## Rollout Plan

- Behind feature flag; consumed by the PWA.

## Success Metrics

- Endpoint success rates; contract conformance.

## Related Documentation

- Parent: [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md)

## Future Considerations

- Public API versioning for third-party clients.