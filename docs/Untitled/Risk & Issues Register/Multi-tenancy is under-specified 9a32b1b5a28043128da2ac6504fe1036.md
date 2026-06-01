# Multi-tenancy is under-specified

Category: Architecture, Security
Description: Docs mention shop-specific domains, tenant isolation, server-side tenant resolution, and rejection of unknown tenants — but implementation details are not shown in the route inventory or architecture docs.
Impact: One tenant data leak can kill the product; requires tenant-scoped queries, policies, object keys, signed URLs, cache keys, queue jobs, logs, and admin overrides. Shops/customers need confidence data does not cross shops.
Issue ID: ISS-12
Notes & Decisions: Cross-shop profile portability is explicitly deferred until single-tenant isolation is proven.
Priority: P0
Recommended Resolution: Create mandatory tenant-context middleware and automated tests proving isolation; avoid cross-shop profile portability until single-tenant isolation is proven.
Related ADRs: ADR Pack (tenancy/isolation)
Related Documents: HLD, Reference Architecture
Related Phases: Phase 3+ (tenant context)
Root Cause: Multi-tenancy is declared as a principle, not designed as a data-access invariant.
Severity: Critical
Status: Open

**Description**

Docs mention shop-specific domains, tenant isolation, server-side tenant resolution, and rejection of unknown tenants. But the implementation details are not shown in the route inventory or architecture docs.

**Root cause**

Multi-tenancy is declared as a principle, not designed as a data-access invariant.

### Impact

- **Business:** One tenant data leak can kill the product.
- **Technical:** Requires tenant-scoped queries, policies, object keys, signed URLs, cache keys, queue jobs, logs, and admin overrides.
- **Customer:** Customers and shops need confidence their data does not cross shops.
- **Risk if unresolved:** Cross-tenant data leakage.

**Recommended resolution**

Create mandatory tenant-context middleware and automated tests proving isolation. Avoid cross-shop profile portability until single-tenant shop isolation is proven.

**Related references**

- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)
- [Reference Architecture](https://app.notion.com/p/Reference-Architecture-36ff29d2a6b181a3a17ad985ffef1d0c?pvs=21)