# Frontend/backend API contract discipline is weak

Category: Architecture, Engineering Quality
Description: Docs repeatedly say APIs should be documented, routes aligned, and frontend should call backend REST endpoints — implying current contract discipline is not strong enough.
Impact: Frontend integration slows and breaks often; drift between Laravel responses and Next.js expectations. UI errors and broken flows.
Issue ID: ISS-20
Notes & Decisions: —
Priority: P1
Recommended Resolution: Introduce OpenAPI or typed API contracts with CI checks; or markdown contracts plus fixture-based frontend tests.
Related ADRs: ADR Pack (API contracts)
Related Documents: RFC, Implementation Plan
Related Phases: All phases (integration)
Root Cause: No generated API schema, typed client, or contract tests are described.
Severity: Medium
Status: Open

**Description**

Docs repeatedly say APIs should be documented, routes should be aligned, and frontend should call backend REST endpoints. That wording implies current contract discipline is not strong enough.

**Root cause**

No generated API schema, typed client, or contract tests are described.

### Impact

- **Business:** Frontend integration slows down and breaks often.
- **Technical:** Drift between Laravel responses and Next.js expectations.
- **Customer:** UI errors and broken flows.
- **Risk if unresolved:** Integration regressions during every phase.

**Recommended resolution**

Introduce OpenAPI or typed API contracts with CI checks. Alternatively, markdown contracts plus fixture-based frontend tests.

**Related references**

- [[rfc-phase-4-storage-beauty-intelligence.md](http://rfc-phase-4-storage-beauty-intelligence.md)](../../rfc/rfc-phase-4-storage-beauty-intelligence%20md%207b0d9680123e464e96484dcd92100966.md)
- [implementation-plan/](../../implementation-plan%20dcdd3415ed5441189d05d8599585c8ac.md)