# Product mapping workload is hand-waved

Category: Data, Operations
Description: Docs say products need concern tags, skin-type tags, tone/undertone tags, ingredient tags, avoid tags, explanation templates, and mappings — but there is no serious operating model for who maintains this at scale.
Impact: Scaling beyond 10–40 demo products becomes expensive and error-prone; needs admin/vendor workflows, validation rules, versioning, review, import/export, and conflict resolution. Bad mappings produce bad advice.
Issue ID: ISS-08
Notes & Decisions: Ties to ISS-07: mapping completeness directly gates recommendation quality.
Priority: P0
Recommended Resolution: Add a mapping completeness score, a mapping QA workflow, and a blocked-recommendation state for unmapped products.
Related ADRs: ADR Pack (catalog mapping)
Related Documents: Technical Proposal, Implementation Plan
Related Phases: Phase 5 (catalog mapping)
Root Cause: Data curation is treated as a one-time seed task.
Severity: High
Status: Open

**Description**

Docs say products need concern tags, skin-type tags, tone/undertone tags, ingredient tags, avoid tags, explanation templates, and mappings. But there is no serious operating model for who maintains this at scale.

**Root cause**

Data curation is treated as a one-time seed task.

### Impact

- **Business:** Scaling beyond 10–40 demo products becomes expensive and error-prone.
- **Technical:** Needs admin workflow, vendor workflow, validation rules, versioning, review, import/export, and conflict resolution.
- **Customer:** Bad mappings produce bad advice.
- **Risk if unresolved:** The recommendation engine collapses when catalog size grows.

**Recommended resolution**

Add a mapping completeness score, a mapping QA workflow, and a blocked-recommendation state for unmapped products.

**Related references**

- [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21)
- [implementation-plan/](../../implementation-plan%20dcdd3415ed5441189d05d8599585c8ac.md)