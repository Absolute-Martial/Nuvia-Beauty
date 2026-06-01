# Admin/vendor/customer role boundaries are risky

Category: Product, Security
Description: The system has customer, seller, vendor, admin, and future brand roles. The backend route inventory describes public, customer, vendor, and super-admin route classes but not the beauty-specific permission model in detail.
Impact: Incorrect access could expose private profiles or media; needs resource-level policies, not just role-level route grouping. Customers lose trust if sellers/admins can see too much.
Issue ID: ISS-21
Notes & Decisions: Resource-level (object) authorization required, not just route-group roles. Links to ISS-05 and ISS-12.
Priority: P0
Recommended Resolution: Define beauty-specific permissions: view session, edit mapping, start analysis, view media, save profile, revoke consent, access insights.
Related ADRs: ADR Pack (authz/roles)
Related Documents: Technical Proposal, backend route inventory
Related Phases: Phase 3+ (policies)
Root Cause: Existing commerce roles are being stretched to handle sensitive beauty workflows.
Severity: High
Status: Open

**Description**

The system has customer, seller, vendor, admin, and future brand roles. The backend route inventory describes public, customer, vendor, and super-admin route classes but not the beauty-specific permission model in detail.

**Root cause**

Existing commerce roles are being stretched to handle sensitive beauty workflows.

### Impact

- **Business:** Incorrect access could expose private profiles or media.
- **Technical:** Needs resource-level policies, not just role-level route grouping.
- **Customer:** Customers lose trust if sellers/admins can see too much.
- **Risk if unresolved:** Privilege escalation and data overexposure.

**Recommended resolution**

Define beauty-specific permissions: view session, edit mapping, start analysis, view media, save profile, revoke consent, access insights.

**Related references**

- [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21)
- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)