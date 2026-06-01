# Data model is expanding faster than domain proof

Category: Architecture, Data
Description: Proposed entities include sessions, profiles, snapshots, media assets, AI tasks, results, mappings, recommendations, quota accounts/events, brands, ingredients, consent grants, outcomes, model versions, sampling campaigns, and audit logs.
Impact: Long implementation tail before customer value; migration complexity, unclear ownership, sparse tables, inconsistent lifecycle. Slow product iteration.
Issue ID: ISS-16
Notes & Decisions: Respect the Architectural Runway principle: schema may include brand_id, but brand logic/APIs/UIs are not built in MVP.
Priority: P1
Recommended Resolution: Implement only tables needed for one validated flow; use event logs or JSON metadata temporarily for non-core experimental fields in testing.
Related ADRs: ADR Pack (data model)
Related Documents: HLD, Technical Proposal
Related Phases: Phase 2–5 (schema)
Root Cause: Future capabilities are being converted into schema commitments too early.
Severity: High
Status: Open

**Description**

Proposed entities include sessions, profiles, snapshots, media assets, AI tasks, results, mappings, recommendations, quota accounts, quota events, brands, ingredients, consent grants, outcomes, model versions, sampling campaigns, and audit logs.

**Root cause**

Future capabilities are being converted into schema commitments too early.

### Impact

- **Business:** Long implementation tail before customer value.
- **Technical:** Migration complexity, unclear ownership, sparse tables, inconsistent lifecycle.
- **Customer:** Slow product iteration.
- **Risk if unresolved:** Over-engineered backend with underused tables.

**Recommended resolution**

Implement only tables needed for one validated flow. Use event logs or JSON metadata temporarily for non-core experimental fields in testing.

**Related references**

- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)
- [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21)
- [](../../product/Untitled%2089b0720ce5d2457cb678cc48369d8047.md)