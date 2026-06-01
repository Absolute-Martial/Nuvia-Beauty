# Phase 5.4 — Demo-Mode Fallback

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-5/phase-5.4-demo-mode-fallback.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) › **Phase 5.4**

## Overview

Provides a deterministic demo-mode fallback so the full analysis flow can be demonstrated without live provider calls — returning realistic synthetic results when `DEMO_MODE=true` or the provider is disabled.

## Objectives

- Return realistic synthetic analysis results in demo mode.
- Exercise the full task→result→recompute path without the provider.
- Keep demo output stable and convincing.

## Scope

**In scope**

- Demo-mode branch in the task/normalization path.
- Synthetic AI Skin Analysis + Skin Tone fallback payloads.

**Out of scope**

- Live security gating ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Business Context

Demo mode de-risks the demo ([Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md)) and enables development without provider credentials or cost.

## Functional Requirements

- When demo/disabled, produce deterministic synthetic results.
- Drive the same state transitions and recompute as live mode.

## Technical Requirements

- Demo result fixtures matching the normalized schema.
- Branch selected by `DEMO_MODE`/`ENABLED` flags.

## Architecture Impact

- Adds a fallback path parallel to the live provider path.

## Dependencies

- [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md).

## Detailed Implementation Tasks

- [ ]  Implement demo result fixtures (skin analysis + skin tone).
- [ ]  Branch task/normalization on demo/disabled flags.
- [ ]  Ensure identical state transitions + recompute.
- [ ]  Tests asserting deterministic demo output.

## Deliverables

- Demo-mode fallback + fixtures + tests.

## Testing & Validation Strategy

- Tests verifying demo path produces stable, valid results + recs.

## Acceptance Criteria

- Full analysis flow works end-to-end in demo mode.

## Exit Criteria

- Demo fallback merged and demoable.

## Risks & Mitigations

- **Demo/live divergence** → share the normalization + recompute path.

## Rollout Plan

- Default in non-prod and demos.

## Success Metrics

- 100% demo runs succeed without provider calls.

## Related Documentation

- Parent: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Demo: [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md)

## Future Considerations

- Configurable demo scenarios per persona.