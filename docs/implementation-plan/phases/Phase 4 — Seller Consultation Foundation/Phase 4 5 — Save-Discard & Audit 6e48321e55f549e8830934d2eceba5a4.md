# Phase 4.5 — Save-Discard & Audit

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-4/phase-4.5-save-discard-audit.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) › **Phase 4.5**

## Overview

Finalizes the save/discard semantics and the audit trail for consultations: saving persists a profile snapshot and clears transient media per policy, while discarding cleans up — and both are fully audited.

## Objectives

- Define save vs discard outcomes precisely.
- Persist profile snapshots on save.
- Enforce media retention/cleanup and write audit logs.

## Scope

**In scope**

- Save → snapshot persistence + retention handling.
- Discard → cleanup.
- Audit logging for both.

**Out of scope**

- Long-term history/timeline UI ([Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)).

## Business Context

Clear save/discard rules and audit trails are essential for trust, privacy compliance, and operability.

## Functional Requirements

- Save creates a durable profile snapshot.
- Discard removes transient artifacts per policy.
- Both write audit entries with actor/time/outcome.

## Technical Requirements

- Snapshot creation from session/profile data.
- Retention/cleanup of input media (private buckets).
- Append-only audit log writes.

## Architecture Impact

- Couples session completion to snapshots + audit; basis for history in [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md).

## Dependencies

- [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md), [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) (audit/quota tables).

## Detailed Implementation Tasks

- [ ]  Implement save → snapshot persistence.
- [ ]  Implement discard → cleanup per retention policy.
- [ ]  Write audit logs for save/discard.
- [ ]  Enforce media retention on private inputs.
- [ ]  Feature tests for save/discard + audit assertions.

## Deliverables

- Save/discard logic + audit trail + tests.

## Testing & Validation Strategy

- Feature tests asserting snapshot creation, cleanup, and audit entries.

## Acceptance Criteria

- Save persists snapshots; discard cleans up; both are audited.

## Exit Criteria

- Save/discard + audit merged and tested; Phase 4 complete.

## Risks & Mitigations

- **Residual private media** → enforce retention + verify cleanup.
- **Missing audit entries** → test-asserted audit on every path.

## Rollout Plan

- Ship with Phase 4.

## Success Metrics

- 100% save/discard paths audited; 0 retention violations.

## Related Documentation

- Parent: [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- History: [Phase 8 — Customer Self-Scan & Profile History](../Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) · RFC: [Request for Comments (RFC)](https://app.notion.com/p/Request-for-Comments-RFC-411c875deb6e4bb79489bf2e536e41cc?pvs=21)

## Future Considerations

- Configurable retention windows by consent tier.