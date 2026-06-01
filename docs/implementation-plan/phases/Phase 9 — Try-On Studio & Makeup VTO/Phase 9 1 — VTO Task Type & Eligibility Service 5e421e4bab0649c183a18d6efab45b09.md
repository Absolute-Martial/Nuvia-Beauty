# Phase 9.1 — VTO Task Type & Eligibility Service

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-9/phase-9.1-vto-task-type-eligibility-service.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) › **Phase 9.1**

## Overview

Introduces the makeup virtual try-on (VTO) task type and an eligibility service that determines which products/sessions can use try-on, laying the data + policy foundation for the try-on studio.

## Objectives

- Add a `makeup_vto` task type to the AI task model.
- Determine try-on eligibility per product/session.
- Establish the foundation for VTO orchestration.

## Scope

**In scope**

- `makeup_vto` task type on `beauty_ai_tasks`; eligibility service.

**Out of scope**

- Provider orchestration ([Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)).

## Business Context

Virtual try-on increases purchase confidence; eligibility ensures it's only offered where supported and appropriate.

## Functional Requirements

- Create + track `makeup_vto` tasks.
- Compute eligibility for products/sessions.

## Technical Requirements

- Extend `beauty_ai_tasks` with the VTO task type.
- Eligibility service using product attributes + mappings.

## Architecture Impact

- Extends the AI task model + adds an eligibility decision point.

## Dependencies

- [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md), [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md).

## Detailed Implementation Tasks

- [ ]  Add the `makeup_vto` task type + model support.
- [ ]  Implement the eligibility service.
- [ ]  Define eligibility rules (product/category support).
- [ ]  Unit tests (task type, eligibility decisions).

## Deliverables

- VTO task type + eligibility service + tests.

## Testing & Validation Strategy

- Tests for task creation + eligible/ineligible decisions.

## Acceptance Criteria

- VTO tasks exist; eligibility computed correctly.

## Exit Criteria

- Foundation ready for provider orchestration.

## Risks & Mitigations

- **Offering try-on where unsupported** → strict eligibility rules.

## Rollout Plan

- Backend-only; demo/disabled until provider wired.

## Success Metrics

- Correct eligibility decisions; clean task model.

## Related Documentation

- Parent: [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · Analysis: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)

## Future Considerations

- **Beyond roadmap:** manufacturer feedback loop; custom analysis model + brand portal informing VTO eligibility.