# UX friction is underestimated

Category: Customer Experience, Product
Description: Seller-assisted capture requires consent, photo quality, upload, AI analysis, seller review, correction, recommendation, save/discard, and profile reopen — a multi-step in-store flow.
Impact: Sellers may abandon the tool during busy retail moments; requires very fast UI, offline fallback, partial save, retry, and clear failure states. Customers may distrust photo capture.
Issue ID: ISS-17
Notes & Decisions: Alternative: start with a no-photo seller questionnaire and product recommendation.
Priority: P0
Recommended Resolution: Time-box the consultation flow to under 2 minutes and test with real users.
Related ADRs: —
Related Documents: White Paper, Technical Proposal
Related Phases: Phase 6–7 (consultation UX)
Root Cause: The docs assume customers and sellers will tolerate a multi-step flow in-store.
Severity: High
Status: Open

**Description**

Seller-assisted capture requires consent, photo quality, upload, AI analysis, seller review, correction, recommendation, save/discard, and profile reopen.

**Root cause**

The docs assume customers and sellers will tolerate a multi-step flow in-store.

### Impact

- **Business:** Sellers may abandon the tool during busy retail moments.
- **Technical:** Requires extremely fast UI, offline fallback, partial save, retry, and clear failure states.
- **Customer:** Customers may not want photos taken or may distrust why data is collected.
- **Risk if unresolved:** Adoption fails despite technical success.

**Recommended resolution**

Time-box the consultation flow to under 2 minutes and test with real users.

**Alternative approaches**

Start with a no-photo seller questionnaire and product recommendation.

**Related references**

- [White Paper — AI Confidence Shopping](https://app.notion.com/p/White-Paper-AI-Confidence-Shopping-36ff29d2a6b18172ab84c0006ac1ad9e?pvs=21)
- [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21)