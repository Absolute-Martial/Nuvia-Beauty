# Consent model is over-specified but under-implemented

Category: Architecture, Privacy
Description: T0–T4 consent is described extensively, but the gap analysis says consent tiers and ecosystem entities are Notion-only design concepts not present in the repo.
Impact: Dangerous privacy claims may be made before the system can enforce them; retrofitting consent into media, profiles, recommendations, analytics, and brand sharing later will be painful; users may believe they control data when enforcement is incomplete.
Issue ID: ISS-04
Notes & Decisions: User: consent comes under post-MVP for now. Alternative: remove all brand-sharing claims until consent infrastructure is implemented and tested. — RESOLVED 2026-05-31: tiered consent (T0–T4) moved to post-MVP in the scope doc; scope notes added to HLD, White Paper, and Concept Paper. MVP now captures only basic capture consent.
Priority: P0
Recommended Resolution: Implement consent as a hard dependency before profile persistence or brand-facing features.
Related ADRs: ADR Pack (consent/privacy)
Related Documents: HLD, Gap Analysis, Product Scope & Release Boundaries
Related Phases: Post-MVP (consent enforcement)
Root Cause: Privacy architecture exists as narrative before an enforceable data model, policy checks, revocation semantics, and audit trails.
Severity: Critical
Status: Resolved
Target Resolution Date: May 31, 2026

**Description**

T0–T4 consent is described extensively, but the gap analysis says consent tiers and ecosystem entities are Notion-only design concepts not in the repo.

**Root cause**

Privacy architecture exists as narrative before an enforceable data model, policy checks, revocation semantics, and audit trails.

### Impact

- **Business:** Dangerous privacy claims may be made before the system can enforce them.
- **Technical:** Later retrofitting consent into media, profiles, recommendations, analytics, and brand sharing will be painful.
- **Customer:** Users may believe they control data when enforcement is incomplete.
- **Risk if unresolved:** Regulatory, trust, and reputational failure.

**Recommended resolution**

Implement consent as a hard dependency before profile persistence or brand-facing features.

**Alternative approaches**

Remove all brand-sharing claims until consent infrastructure is implemented and tested.

<aside>
📌

User decision: consent enforcement is treated as **post-MVP** for now.

</aside>

**Related references**

- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)
- [Gap Analysis — Repo vs Notion Docs](https://app.notion.com/p/Gap-Analysis-Repo-vs-Notion-Docs-ba5bd47ca7c44bc0801097a6fb9ba242?pvs=21)
- [](../../product/Untitled%2089b0720ce5d2457cb678cc48369d8047.md)