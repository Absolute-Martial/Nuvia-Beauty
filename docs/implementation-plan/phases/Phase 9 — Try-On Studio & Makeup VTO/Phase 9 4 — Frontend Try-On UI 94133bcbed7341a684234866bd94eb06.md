# Phase 9.4 — Frontend Try-On UI

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-9/phase-9.4-frontend-try-on-ui.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) › **Phase 9.4**

## Overview

Delivers the customer-facing try-on studio UI: select eligible products, launch VTO, view results, and save/discard — integrated into the storefront/PWA.

## Objectives

- Let customers try on eligible products virtually.
- Show VTO status + results smoothly.
- Support saving/discarding try-on results.

## Scope

**In scope**

- Try-on studio UI (product selection, VTO launch, results, save/discard).

**Out of scope**

- Vendor/admin eligibility mapping ([Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)).

## Business Context

The try-on studio is the customer-visible payoff of VTO, driving engagement and purchase confidence.

## Functional Requirements

- Show eligible products; launch try-on.
- Display status + results; save/discard.

## Technical Requirements

- Consume try-on APIs; handle async status + media.
- Graceful handling of ineligible products/errors.

## Architecture Impact

- New frontend consumer of the try-on APIs.

## Dependencies

- [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md).

## Detailed Implementation Tasks

- [ ]  Build product selection (eligibility-aware).
- [ ]  Launch VTO + show status.
- [ ]  Render results; implement save/discard.
- [ ]  Handle errors/ineligibility gracefully.
- [ ]  Component + E2E tests (demo mode).

## Deliverables

- Try-on studio UI + tests.

## Testing & Validation Strategy

- E2E tests for select→try-on→results→save/discard in demo mode.

## Acceptance Criteria

- Customers complete a try-on and save/discard results.

## Exit Criteria

- Try-on UI merged and demoable.

## Risks & Mitigations

- **Confusing async/media UX** → clear status + previews.

## Rollout Plan

- Behind feature flag; demo mode first.

## Success Metrics

- Try-on completion + save rates.

## Related Documentation

- Parent: [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- APIs: [Phase 9 — Try-On Studio & Makeup VTO](../Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)

## Future Considerations

- **Beyond roadmap:** live AR try-on; shareable try-on looks.