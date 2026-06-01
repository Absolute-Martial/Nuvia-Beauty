# Phase 6.4 — Deployment Notes & Checklist

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-6/phase-6.4-deployment-notes-checklist.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md) › **Phase 6.4**

## Overview

Assembles the deployment notes and pre-push checklist needed to deploy the demo build reliably, including environment configuration, service startup order, and rollback steps.

## Objectives

- Provide a complete deploy/run checklist for the demo build.
- Document env config + service startup order.
- Reference rollback procedures.

## Scope

**In scope**

- Deployment checklist + environment notes for the demo.

**Out of scope**

- Production hardening (deployment docs proper).

## Business Context

A clear checklist prevents demo-day deployment mistakes and enables quick recovery.

## Functional Requirements

- Step-by-step deploy/run instructions.
- Env variables + service ports documented.
- Rollback steps referenced.

## Technical Requirements

- Align with `.env.example` + service ports (backend 8000; storefront 3003; admin 3002; vendor 3004; MySQL 3306; Redis 6379; S3 9000/9001).
- Cross-link production-readiness + rollback docs.

## Architecture Impact

- None; operational documentation.

## Dependencies

- [Phase 3.8 — Staging Smoke Test](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation/Phase%203%208%20%E2%80%94%20Staging%20Smoke%20Test%204bd566ab01f447d9ba81d9ad3fc8e67f.md).

## Detailed Implementation Tasks

- [ ]  Document env config + startup order.
- [ ]  Write the pre-push/deploy checklist.
- [ ]  Reference rollback procedure.
- [ ]  Validate by following it on a clean environment.

## Deliverables

- Deployment notes + pre-push checklist.

## Testing & Validation Strategy

- Follow the checklist on a fresh environment to confirm completeness.

## Acceptance Criteria

- The demo build deploys cleanly by following the checklist.

## Exit Criteria

- Checklist validated; ready for demo deployment.

## Risks & Mitigations

- **Misconfig on demo day** → checklist + dry-run deploy.

## Rollout Plan

- Used for demo deployment; references prod docs for later.

## Success Metrics

- 0 deploy-blocking issues during dry run.

## Related Documentation

- Parent: [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Deployment: [[production-readiness.md](http://production-readiness.md)](../../../deployment/production-readiness%20md%204a0fae0daf6e4e87ab5a75757909ec84.md) · [[rollback.md](http://rollback.md)](../../../deployment/rollback%20md%20ce2b95b2b57b431891c2528be2613c84.md)

## Future Considerations

- CI/CD pipeline for one-click demo deploys.