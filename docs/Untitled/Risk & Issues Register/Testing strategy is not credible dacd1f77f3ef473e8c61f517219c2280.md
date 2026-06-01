# Testing strategy is not credible

Category: Engineering Quality
Description: Docs say run php artisan test, build frontends, and smoke-test routes. That is not enough for a system handling private media, AI tasks, consent, and recommendations.
Impact: False confidence before demo/pilot; missing tests for auth, tenant isolation, signed-URL expiry, bucket privacy, recommendation correctness, consent revocation, provider failure, queue retry, deletion. Bugs show up during real consultations.
Issue ID: ISS-14
Notes & Decisions: Minimum test set should gate the move from Demo-only to Pilot MVP (links to ISS-02 re-baseline).
Priority: P0
Recommended Resolution: Define the minimum required automated tests before any real pilot.
Related ADRs: —
Related Documents: Implementation Plan, deployment README
Related Phases: All phases (test gates)
Root Cause: Test plan is mostly build/smoke validation.
Severity: Critical
Status: Open

**Description**

Docs say run `php artisan test`, build frontends, and smoke test routes. That is not enough for a system handling private media, AI tasks, consent, and recommendations.

**Root cause**

Test plan is mostly build/smoke validation.

### Impact

- **Business:** False confidence before demo or pilot.
- **Technical:** Missing tests for auth, tenant isolation, signed-URL expiry, bucket privacy, recommendation correctness, consent revocation, provider failure, queue retry, deletion.
- **Customer:** Bugs show up during real consultations.
- **Risk if unresolved:** Hidden defects become public failures.

**Recommended resolution**

Define the minimum required automated tests before any real pilot.

**Related references**

- [implementation-plan/](../../implementation-plan%20dcdd3415ed5441189d05d8599585c8ac.md)
- [[README.md](http://README.md)](../../deployment/README%20md%20877b8d43b0694960aa6008fb576f0016.md)