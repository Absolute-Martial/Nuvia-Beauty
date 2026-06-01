# “Zero trust” is branding, not an implemented security model

Category: Architecture, Security
Description: HLD claims zero-trust access (per-request auth, least privilege, mutual service authentication, default deny, logged access decisions), but the actual repo is a Laravel backend plus MySQL/Redis/S3/provider APIs — not a verified service mesh or mTLS design.
Impact: Overclaiming security posture invites scrutiny; engineers lack concrete policies, middleware, token scopes, and audit contracts; private facial/media data may be exposed through authorization gaps.
Issue ID: ISS-05
Notes & Decisions: Approach: call it “backend-mediated authorization” until actual zero-trust controls exist. (Source-doc wording fix deferred per 'record only' decision.) — RESOLVED 2026-05-31: HLD section renamed to ‘Backend-mediated authorization’; zero-trust branding replaced and reframed as target controls.
Priority: P0
Recommended Resolution: Replace vague zero-trust language with explicit controls: route middleware, policies, object-level authorization, signed-URL TTL, audit events, and test cases.
Related ADRs: ADR Pack (security/auth)
Related Documents: HLD, Reference Architecture
Related Phases: Phase 3+ (auth & policies)
Root Cause: Security principles are stated at enterprise level without matching implementation details.
Severity: High
Status: Resolved
Target Resolution Date: May 31, 2026

**Description**

HLD claims zero-trust access with per-request auth, least privilege, mutual service authentication, default deny, and logged access decisions. But the actual repo architecture is a Laravel backend plus MySQL/Redis/S3/provider APIs, not a verified service mesh or mTLS design.

**Root cause**

Security principles are stated at enterprise level without matching implementation details.

### Impact

- **Business:** Overclaiming security posture invites scrutiny.
- **Technical:** Engineers lack concrete policies, middleware, token scopes, and audit contracts.
- **Customer:** Private facial/media data may be exposed through authorization gaps.
- **Risk if unresolved:** “Zero trust” becomes false assurance.

**Recommended resolution**

Replace vague zero-trust language with explicit controls: route middleware, policies, object-level authorization, signed-URL TTL, audit events, test cases.

**Approaches**

Call it “backend-mediated authorization” until actual zero-trust controls exist.

**Related references**

- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)
- [Reference Architecture](https://app.notion.com/p/Reference-Architecture-36ff29d2a6b181a3a17ad985ffef1d0c?pvs=21)