# MVP boundary is contaminated by ecosystem ambition

Category: Product
Description: MVP holds seller consultation, AI analysis, profiles, deterministic recommendations, quota, consent, reopen links, and demo fallback — but docs also add brand ecosystem, manufacturer feedback, custom ML, cross-shop loyalty, VTO, self-scan, and insights.
Impact: Scope explosion before market validation; premature data-model and consent complexity; core seller/customer experience may stay clunky while low-probability future features consume attention.
Issue ID: ISS-03
Notes & Decisions: Approach: build two MVPs separately — retail consultation MVP now, brand-insights platform post-MVP. Partially addressed by the Product Scope & Release Boundaries doc.
Priority: P0
Recommended Resolution: Cut MVP to one flow.
Related ADRs: ADR 0011 (Modular Monolith)
Related Documents: Product Scope & Release Boundaries, http://mvp-boundary.md, Technical Proposal, White Paper
Related Phases: MVP vs Post-MVP (Phase 10–11)
Root Cause: Product vision is not separated from buildable MVP.
Severity: Critical
Status: Open

**Description**

The MVP contains seller consultation, AI analysis, profiles, deterministic recommendations, quota, consent, reopen links, and demo fallback. Around it, docs add brand ecosystem, manufacturer feedback, custom ML, cross-shop loyalty, VTO, self-scan, and insights.

**Root cause**

Product vision is not separated from buildable MVP.

### Impact

- **Business:** Scope explosion before market validation.
- **Technical:** Premature data model and consent architecture complexity.
- **Customer:** The core seller/customer experience may remain clunky while low-probability future features consume attention.
- **Risk if unresolved:** The project fails by trying to be a platform before proving one workflow.

**Recommended resolution**

Cut MVP to one flow.

**Approaches**

Build two MVPs separately: a retail consultation MVP now and a brand-insights platform later (post-MVP).

**Related references**

- [](../../product/Untitled%2089b0720ce5d2457cb678cc48369d8047.md)
- [[mvp-boundary.md](http://mvp-boundary.md)](../../product/mvp-boundary%20md%202ab83c83a45b486a886ceff5e5304aa5.md)
- [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21)