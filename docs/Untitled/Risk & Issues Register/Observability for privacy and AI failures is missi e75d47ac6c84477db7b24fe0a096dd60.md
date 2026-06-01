# Observability for privacy and AI failures is missing

Category: Operations, Security
Description: The system needs to know when media access is denied, signed URLs are issued, provider calls fail, quota is exceeded, recommendations are generated, and consent changes. Docs mention audit logging but not operational dashboards or alerts.
Impact: Incidents are discovered by users, not operators; missing structured logs, metrics, traces, alert thresholds, and audit review. Support cannot explain failures.
Issue ID: ISS-24
Notes & Decisions: For demo-only, log every sensitive event to a protected admin table and review manually. Overlaps with ISS-13.
Priority: P0
Recommended Resolution: Add minimum observability: task failure rate, provider latency, signed-URL issuance, media deletion failures, consent changes, cross-tenant denial events.
Related ADRs: ADR Pack (audit/observability)
Related Documents: HLD, Reference Architecture
Related Phases: Phase 8–9 (observability)
Root Cause: Audit is described as a module, not an operational capability.
Severity: Critical
Status: Open

**Description**

The system needs to know when media access is denied, signed URLs are issued, provider calls fail, quota is exceeded, recommendations are generated, and consent is changed. Docs mention audit logging but not operational dashboards or alerts.

**Root cause**

Audit is described as a module, not an operational capability.

### Impact

- **Business:** Incidents are discovered by users, not operators.
- **Technical:** Missing structured logs, metrics, traces, alert thresholds, and audit review.
- **Customer:** Support cannot explain failures.
- **Risk if unresolved:** Silent privacy/security failures.

**Recommended resolution**

Add minimum observability: task failure rate, provider latency, signed-URL issuance, media deletion failures, consent changes, cross-tenant denial events.

**Approaches**

For demo-only, log every sensitive event to a protected admin table and review manually.

**Related references**

- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)
- [Reference Architecture](https://app.notion.com/p/Reference-Architecture-36ff29d2a6b181a3a17ad985ffef1d0c?pvs=21)