# Phase 8.1 — Customer Self-Scan Backend

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-8/phase-8.1-customer-self-scan-backend.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) › **Phase 8.1**

## Overview

Builds the backend that lets customers initiate their own beauty scan (self-service), creating sessions/scans under quota and policy controls, and wiring into the existing analysis pipeline.

## Objectives

- Enable customer-initiated scans.
- Enforce per-customer access + quota.
- Reuse the consultation/analysis pipeline for self-scans.

## Scope

**In scope**

- `CustomerSelfScanController`, `CustomerScanPolicy`, `CustomerQuotaPolicy`; scan creation + lifecycle.

**Out of scope**

- Media quality/privacy gate ([Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)); APIs surface ([Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)).

## Business Context

Self-scan extends beauty intelligence directly to customers, increasing engagement and personalization beyond in-store consultations.

## Functional Requirements

- Customer can start a self-scan session.
- Access + quota enforced per customer.
- Self-scans feed the analysis pipeline.

## Technical Requirements

- Controller + policies (`CustomerScanPolicy`, `CustomerQuotaPolicy`).
- Reuse session/task model from [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)/[Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md).

## Architecture Impact

- Adds a customer-initiated entry point into the beauty pipeline.

## Dependencies

- [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md), [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md), [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).

## Detailed Implementation Tasks

- [ ]  Implement `CustomerSelfScanController` (create/lifecycle).
- [ ]  Implement `CustomerScanPolicy` + `CustomerQuotaPolicy`.
- [ ]  Wire self-scans into the analysis pipeline.
- [ ]  Unit + feature tests (access, quota, lifecycle).

## Deliverables

- Self-scan controller + policies + tests.

## Testing & Validation Strategy

- Tests for scan creation, access denial, quota enforcement.

## Acceptance Criteria

- Customers can self-scan within access + quota limits.

## Exit Criteria

- Self-scan backend merged and policy-gated.

## Risks & Mitigations

- **Abuse/quota exhaustion** → `CustomerQuotaPolicy` + rate limits.

## Rollout Plan

- Behind tenant + feature flag; staging first.

## Success Metrics

- Successful self-scans within quota; 0 policy bypasses.

## Related Documentation

- Parent: [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Sessions: [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) · Analysis: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)

## Future Considerations

- Customer accounts + saved scan history.