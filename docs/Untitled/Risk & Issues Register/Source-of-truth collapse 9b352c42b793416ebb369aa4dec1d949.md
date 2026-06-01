# Source-of-truth collapse

Category: Documentation & Governance
Description: Docs, roadmap, gap analysis, and repo docs disagree on product name, repo path, phase status, built/planned state, and architecture style.
Impact: Stakeholders overestimate readiness; engineers build on stale assumptions; customers may be promised unstable/demo-grade flows. Architecture decisions become performative, not executable.
Issue ID: ISS-01
Notes & Decisions: Approach: freeze Notion as strategy docs and GitHub as implementation truth. This register + Status Matrix is the first step toward the canonical matrix. — RESOLVED 2026-05-31: canonical Status Matrix + Risk Register created; Documentation Hub now names them as the single source of truth for status.
Priority: P0
Recommended Resolution: Create one canonical status matrix (feature, doc owner, implementation owner, repo commit, test evidence, deployment status).
Related ADRs: —
Related Documents: Documentation Hub, Roadmap, Gap Analysis, Product Scope & Release Boundaries
Related Phases: All
Root Cause: No enforced canonical source of truth; docs are copied, mirrored, and reinterpreted across Notion and GitHub.
Severity: Critical
Status: Resolved
Target Resolution Date: May 31, 2026

**Description**

The docs, roadmap, gap analysis, and repo docs disagree on product name, repo path, phase status, built/planned state, and architecture style.

**Root cause**

No enforced canonical source of truth; docs are being copied, mirrored, and reinterpreted across Notion and GitHub.

### Impact

- **Business:** Stakeholders will overestimate readiness. Investors/judges/partners may see inconsistency as immaturity.
- **Technical:** Engineers will build against stale assumptions and duplicate or reverse decisions.
- **Customer:** Customers may be promised flows that are unstable, incomplete, or only demo-grade.
- **Risk if unresolved:** Architecture decisions become performative instead of executable.

**Recommended resolution**

Create one canonical status matrix: feature, doc owner, implementation owner, repo commit, test evidence, deployment status.

**Approaches**

Freeze Notion as strategy docs and GitHub as implementation truth.

**Related references**

- [Documentation Hub](https://app.notion.com/p/Documentation-Hub-36ff29d2a6b1813387f2ef5234b31127?pvs=21)
- [Roadmap — Nuvia Beauty](../../implementation-plan/Roadmap%20%E2%80%94%20Nuvia%20Beauty%20d276dacddb7e4b7bb92399a12ec3c10c.md)
- [Gap Analysis — Repo vs Notion Docs](https://app.notion.com/p/Gap-Analysis-Repo-vs-Notion-Docs-ba5bd47ca7c44bc0801097a6fb9ba242?pvs=21)
- [](../../product/Untitled%2089b0720ce5d2457cb678cc48369d8047.md)