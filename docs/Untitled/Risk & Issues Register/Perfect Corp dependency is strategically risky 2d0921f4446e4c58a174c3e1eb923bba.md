# Perfect Corp dependency is strategically risky

Category: Architecture, External Dependency
Description: The system positions Perfect Corp / YouCam as the cold-start analysis provider while also planning eventual custom-model replacement.
Impact: Unit economics may fail if provider calls are expensive; requires async tasks, retries, polling, provider normalization, quota accounting, demo fallback, and failure dashboards. Analysis delays/failures break the consultation experience.
Issue ID: ISS-09
Notes & Decisions: Alternative: launch with seller-entered profile attributes (exact definition TBD — user flagged this needs clarification). — RESOLVED 2026-05-31: documented provider analysis as an optional enhancement (not core value) in the Technical Proposal principles and White Paper. Seller-entered-attributes alternative still needs definition.
Priority: P0
Recommended Resolution: Treat provider integration as an optional enhancement, not the core value proposition.
Related ADRs: ADR Pack (provider integration)
Related Documents: Roadmap, Gap Analysis, Technical Proposal
Related Phases: Phase 5 (Perfect Corp P0, demo-first)
Root Cause: Provider dependency is accepted before pricing, quotas, latency, accuracy, licensing, data retention, and fallback behavior are fully operationalized.
Severity: High
Status: Resolved
Target Resolution Date: May 31, 2026

**Description**

The system positions Perfect Corp / YouCam as the cold-start analysis provider while also planning eventual custom-model replacement.

**Root cause**

Provider dependency is accepted before pricing, quotas, latency, accuracy, licensing, data retention, and fallback behavior are fully operationalized.

### Impact

- **Business:** Unit economics may fail if provider calls are expensive.
- **Technical:** Requires async tasks, retries, polling, provider normalization, quota accounting, demo fallback, and failure dashboards.
- **Customer:** Analysis delays or failures break the consultation experience.
- **Risk if unresolved:** The product becomes dependent on an external API it cannot control.

**Recommended resolution**

Treat provider integration as an optional enhancement, not the core value proposition.

**Alternative approaches**

Launch with seller-entered profile attributes. *(User noted the exact meaning of this alternative still needs clarification.)*

**Related references**

- [Roadmap — Nuvia Beauty](../../implementation-plan/Roadmap%20%E2%80%94%20Nuvia%20Beauty%20d276dacddb7e4b7bb92399a12ec3c10c.md)
- [Gap Analysis — Repo vs Notion Docs](https://app.notion.com/p/Gap-Analysis-Repo-vs-Notion-Docs-ba5bd47ca7c44bc0801097a6fb9ba242?pvs=21)
- [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21)