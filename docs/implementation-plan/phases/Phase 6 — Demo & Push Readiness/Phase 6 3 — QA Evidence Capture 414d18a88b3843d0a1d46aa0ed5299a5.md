# Phase 6.3 — QA Evidence Capture

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-6/phase-6.3-qa-evidence-capture.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md) › **Phase 6.3**

## Overview

Captures verifiable QA evidence (screenshots, recordings, logs, test results) demonstrating that each demoed capability works, supporting reviewer confidence and traceability.

## Objectives

- Capture evidence for each key flow.
- Store evidence in the evidence/ area.
- Map evidence to acceptance criteria.

## Scope

**In scope**

- Evidence capture for consultation, recommendations, analysis (demo), and storage.

**Out of scope**

- Deployment checklist ([Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md)).

## Business Context

Evidence makes readiness auditable and builds stakeholder trust ahead of go-live.

## Functional Requirements

- Each demoed flow has captured evidence.
- Evidence is linked to its acceptance criteria.

## Technical Requirements

- Organized evidence artifacts (screens/recordings/logs/test output).
- Index mapping evidence → criteria.

## Architecture Impact

- None; QA artifacts.

## Dependencies

- [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md).

## Detailed Implementation Tasks

- [ ]  Capture screenshots/recordings of each flow.
- [ ]  Collect relevant logs + test results.
- [ ]  Store in evidence/ with an index.
- [ ]  Map each artifact to acceptance criteria.

## Deliverables

- Evidence set + evidence index.

## Testing & Validation Strategy

- Review evidence against the demo script + criteria.

## Acceptance Criteria

- Every key flow has linked, reviewable evidence.

## Exit Criteria

- Evidence pack complete for readiness review.

## Risks & Mitigations

- **Stale/missing evidence** → capture during the final dry run.

## Rollout Plan

- Compiled before the readiness review.

## Success Metrics

- 100% key flows evidenced.

## Related Documentation

- Parent: [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Testing: [testing/](../../../testing%20fb05a1e82065477d8aaead149521944f.md)

## Future Considerations

- Automated evidence capture in CI.