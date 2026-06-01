# Business assumptions are unvalidated

Category: Business, Product
Description: The docs assume SME retailers need AI consultation, sellers will adopt it, customers will consent, brands want insights, and saved profiles drive repeat visits.
Impact: Build may solve a problem buyers do not prioritize; engineering invests before validating willingness to use/pay. Customers may reject photo capture, profile storage, or brand data sharing.
Issue ID: ISS-25
Notes & Decisions: Additional approach: concierge MVP with a paper/profile form and manually generated recommendations. Reinforces the MVP-success definition in the scope doc.
Priority: P0
Recommended Resolution: Run 5–10 manual seller consultations before building more architecture.
Related ADRs: —
Related Documents: White Paper, Product Scope & Release Boundaries
Related Phases: Pre-MVP validation
Root Cause: Product strategy is built from plausible narrative, not demonstrated demand.
Severity: High
Status: Open

**Description**

The docs assume SME retailers need AI consultation, sellers will adopt it, customers will consent, brands want insights, and saved profiles will drive repeat visits.

**Root cause**

Product strategy is built from plausible narrative, not demonstrated demand.

### Impact

- **Business:** Build may solve a problem that buyers do not prioritize.
- **Technical:** Engineering invests in features before validating willingness to use/pay.
- **Customer:** Customers may reject photo capture, profile storage, or brand data sharing.
- **Risk if unresolved:** Technically impressive product with weak adoption.

**Recommended resolution**

Run 5–10 manual seller consultations before building more architecture.

**Additional approaches**

Concierge MVP with a paper/profile form and manually generated recommendations.

**Related references**

- [White Paper — AI Confidence Shopping](https://app.notion.com/p/White-Paper-AI-Confidence-Shopping-36ff29d2a6b18172ab84c0006ac1ad9e?pvs=21)
- [](../../product/Untitled%2089b0720ce5d2457cb678cc48369d8047.md)