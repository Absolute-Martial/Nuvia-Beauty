# “Confidence shopping” value is not sufficiently measurable

Category: Business, Product
Description: Docs list success indicators (recommendation acceptance, confused/no-decision rate, reopen usage, seller adoption, return-rate change) but no measurement instrumentation plan is defined.
Impact: You cannot prove the product works; missing event taxonomy, funnels, cohorts, baseline comparison, and experiment design. Product decisions may be based on anecdotes.
Issue ID: ISS-18
Notes & Decisions: User idea: a full management system — vendors manage products/auditing, customers get a beauty-care system, and ordering/search per vendor. Captured as a post-MVP direction to validate, not MVP scope.
Priority: P1
Recommended Resolution: Define analytics events before pilot; run concierge tests manually before building analytics.
Related ADRs: —
Related Documents: White Paper
Related Phases: Pilot MVP (instrumentation)
Root Cause: Research goals are not translated into analytics requirements.
Severity: Medium
Status: Open

**Description**

Docs list success indicators like recommendation acceptance, confused/no-decision rate, reopen usage, seller adoption, and return-rate change. But no measurement instrumentation plan is defined.

**Root cause**

Research goals are not translated into analytics requirements.

### Impact

- **Business:** You cannot prove the product works.
- **Technical:** Missing event taxonomy, funnels, cohorts, baseline comparison, and experiment design.
- **Customer:** Product decisions may be based on anecdotes.
- **Risk if unresolved:** No credible evidence for investors, judges, shops, or partners.

**Recommended resolution**

Define analytics events before pilot. Run concierge tests manually before building analytics.

**Notes**

User also raised a broader direction: give the **vendor/seller** a full management system (managing products, auditing, ordering from specific vendors, product search) and give the **customer** a beauty-care system, since the whole system is for physical retail. Captured here as a **post-MVP product direction to validate**, not MVP scope.

**Related references**

- [White Paper — AI Confidence Shopping](https://app.notion.com/p/White-Paper-AI-Confidence-Shopping-36ff29d2a6b18172ab84c0006ac1ad9e?pvs=21)