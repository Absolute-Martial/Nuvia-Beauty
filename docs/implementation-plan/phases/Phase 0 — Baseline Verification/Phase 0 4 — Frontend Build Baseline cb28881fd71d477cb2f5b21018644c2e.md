# Phase 0.4 — Frontend Build Baseline

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-0/phase-0.4-frontend-build-baseline.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) › **Phase 0.4**

## Overview

Confirms that all three frontends — storefront, admin-panel, and vendor-portal — install dependencies and build/run cleanly against the documented Node/Next/React versions, and that they can reach the API.

## Objectives

- Verify each frontend installs and builds.
- Confirm dev servers run on their assigned ports.
- Confirm API base URL configuration resolves.

## Scope

**In scope**

- storefront (3003), admin-panel (3002), vendor-portal (3004).
- Next.js 15.5.18 / React 19.2.6 build + dev run.

**Out of scope**

- Feature-level UI verification (later phases).

## Business Context

Three frontends must stay buildable to demo and ship the beauty experience; a build baseline prevents integration surprises during feature phases and the demo ([Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md)).

## Functional Requirements

- `install` + `build` succeed for each app.
- Dev servers start and serve on the correct ports.
- API base URL env resolves to the running backend.

## Technical Requirements

- Node version compatible with Next 15.5.18.
- Consistent `NEXT_PUBLIC_*` API base URL configuration.
- Lockfiles committed and respected.

## Architecture Impact

- None to backend. Confirms the client topology and API connectivity assumptions.

## Dependencies

- [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) (env), [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) (API reachable).

## Detailed Implementation Tasks

- [ ]  Install dependencies for each frontend from lockfiles.
- [ ]  Run production `build` for each; capture output.
- [ ]  Start each dev server and confirm its port.
- [ ]  Confirm each app reads the correct API base URL and can call a health endpoint.

## Deliverables

- Build logs per frontend.
- Verified API base URL configuration.

## Testing & Validation Strategy

- Build smoke for each app.
- Manual load of each dev server root.
- API connectivity check from each client.

## Acceptance Criteria

- All three frontends build and run.
- Each reaches the API.

## Exit Criteria

- Frontend build baseline recorded for all three apps.

## Risks & Mitigations

- **Version mismatch** → pin Node/Next/React; commit lockfiles.
- **CORS/base URL misconfig** → standardize env and document CORS expectations.

## Rollout Plan

- Local + CI. No production impact.

## Success Metrics

- 3/3 frontends build green.
- 0 API-connectivity failures on baseline.

## Related Documentation

- Parent: [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- API: [[api-contracts.md](http://api-contracts.md)](../../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md)

## Future Considerations

- Add CI build matrix across the three apps.
- Shared UI/config package to reduce drift.