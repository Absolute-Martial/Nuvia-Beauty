# Raw facial media risk is underestimated

Category: Operations, Privacy
Description: Docs correctly prohibit raw images in MySQL and public buckets, but the system still depends on private media storage, signed URLs, provider transfer, retention, discard, and future model training.
Impact: A single bucket-policy error or signed-URL leak can destroy trust; requires retention jobs, access logs, deletion verification, provider deletion policy, and object lifecycle management. Customers may reject facial capture entirely.
Issue ID: ISS-06
Notes & Decisions: Alternative: allow photo capture only after consent and deletion controls are proven; post-MVP, offer a no-photo deterministic consultation path.
Priority: P0
Recommended Resolution: Build a media threat model and deletion audit before expanding AI workflows.
Related ADRs: ADR Pack (storage/media)
Related Documents: HLD, Gap Analysis, deployment README
Related Phases: Phase 5 (media + analysis)
Root Cause: Media lifecycle is treated as a storage feature, not a privacy-critical compliance subsystem.
Severity: Critical
Status: Open

**Description**

Docs correctly prohibit raw images in MySQL and public buckets, but the system still depends on private media storage, signed URLs, provider transfer, retention, discard, and future model training.

**Root cause**

Media lifecycle is treated as a storage feature, not a privacy-critical compliance subsystem.

### Impact

- **Business:** A single bucket policy error or signed-URL leak can destroy trust.
- **Technical:** Requires retention jobs, access logs, deletion verification, provider deletion policy, and object lifecycle management.
- **Customer:** Facial images are extremely sensitive; customers may reject capture entirely.
- **Risk if unresolved:** Privacy incident, demo failure, or inability to pilot with real users.

**Recommended resolution**

Build a media threat model and deletion audit before expanding AI workflows.

**Alternative approaches**

Use photo capture only after consent and deletion controls are proven; post-MVP, offer a no-photo deterministic consultation.

**Related references**

- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)
- [Gap Analysis — Repo vs Notion Docs](https://app.notion.com/p/Gap-Analysis-Repo-vs-Notion-Docs-ba5bd47ca7c44bc0801097a6fb9ba242?pvs=21)
- [[README.md](http://README.md)](../../deployment/README%20md%20877b8d43b0694960aa6008fb576f0016.md)