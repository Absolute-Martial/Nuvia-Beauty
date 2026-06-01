# Deterministic recommendation engine may be too weak to justify the product

Category: ML & Strategy, Product
Description: Docs rely heavily on deterministic scoring for explainability and safety, but deterministic tag matching can become shallow, generic, or wrong if product mappings are incomplete.
Impact: If recommendations feel obvious or wrong, the product has no defensible value; requires mapping completeness, taxonomy governance, scoring validation, and feedback loops. Users may see “recommended” products that are just tagged products with marketing copy.
Issue ID: ISS-07
Notes & Decisions: Recommendation quality is a core MVP success metric (see Product Scope & Release Boundaries).
Priority: P0
Recommended Resolution: Define recommendation quality metrics and test sets before adding more features.
Related ADRs: ADR Pack (recommendation engine)
Related Documents: Technical Proposal, HLD, White Paper
Related Phases: Phase 5–6 (recommendations)
Root Cause: The system assumes structured product metadata will be accurate, complete, and maintainable.
Severity: High
Status: Open

**Description**

The docs rely heavily on deterministic scoring for explainability and safety. But deterministic tag matching can easily become shallow, generic, or wrong if product mappings are incomplete.

**Root cause**

The system assumes structured product metadata will be accurate, complete, and maintainable.

### Impact

- **Business:** If recommendations feel obvious or wrong, the product has no defensible value.
- **Technical:** Requires mapping completeness, product taxonomy governance, scoring validation, and feedback loops.
- **Customer:** Users may see “recommended” products that are just tagged products with marketing copy.
- **Risk if unresolved:** Low trust, low adoption, and no measurable improvement over seller memory.

**Recommended resolution**

Define recommendation quality metrics and test sets before adding more features.

**Related references**

- [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21)
- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)
- [](../../product/Untitled%2089b0720ce5d2457cb678cc48369d8047.md)