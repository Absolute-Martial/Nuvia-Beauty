# Legacy demo positioning conflicts with production architecture

Category: Documentation & Governance, Product
Description: Repo README and older public notes previously framed this as a short-term demo effort using Perfect Corp APIs and seeded artifacts, while architecture docs frame it as a scalable, privacy-aware, multi-tenant commercial platform.
Impact: Either the demo is overburdened by architecture or production is polluted by demo shortcuts; demo fallback, seeded data, provider mocks, and production flows may blur. Users may see staged behavior as real capability.
Issue ID: ISS-19
Notes & Decisions: User decision: the project is being carried forward as a startup/pilot codebase, not a short-term event deliverable. Repo-side framing should match that position. — RESOLVED 2026-05-31: current docs and README were reframed to startup/pilot language.
Priority: P0
Recommended Resolution: Separate demo artifacts from production architecture; keep repo and docs framed around the product codebase.
Related ADRs: —
Related Documents: HLD, White Paper, Documentation Hub, repo README
Related Phases: All
Root Cause: Demo speed and production rigor are not separated.
Severity: High
Status: Resolved
Target Resolution Date: May 31, 2026

**Description**

Repo README and older public notes previously framed this as a short-term demo effort using Perfect Corp APIs and seeded artifacts. Architecture docs frame it as a scalable, privacy-aware, multi-tenant commercial platform.

**Root cause**

Demo speed and production rigor are not separated.

### Impact

- **Business:** Either the demo is overburdened by architecture or production is polluted by demo shortcuts.
- **Technical:** Demo fallback, seeded data, provider mocks, and production flows may blur.
- **Customer:** Users may see staged behavior as real capability.
- **Risk if unresolved:** Demo debt becomes product debt.

<aside>
📌

User decision: this is the active product codebase and should be framed as a startup/pilot platform, not a short-term event deliverable.

</aside>

**Recommended resolution**

Separate demo artifacts from production architecture, and keep repo/docs framed around the product codebase.

**Related references**

- [High-Level Design](https://app.notion.com/p/High-Level-Design-36ff29d2a6b18142a5b4e5c386737c9f?pvs=21)
- [Documentation Hub](https://app.notion.com/p/Documentation-Hub-36ff29d2a6b1813387f2ef5234b31127?pvs=21)
