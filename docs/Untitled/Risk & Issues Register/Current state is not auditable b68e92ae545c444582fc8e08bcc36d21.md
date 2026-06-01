# Current state is not auditable

Category: Logic & Consistency
Description: Phase 5 is simultaneously “planned” and “added.” Phase 3 is both a current blocker and apparently bypassed.
Impact: Demo claims become fragile; readiness cannot be defended under scrutiny. Teams may build Phase 7–9 on unstable Phase 3–5 foundations; users hit broken consultation/analysis flows.
Issue ID: ISS-02
Notes & Decisions: Approach (NOT yet approved): replace phase numbering with release milestones Demo Alpha, Pilot MVP, Production Beta. Needs sign-off before changing the Implementation Plan phase structure. — RESOLVED 2026-05-31: added explicit status taxonomy (Implemented / Implemented-unverified / Demo-only / Planned / Rejected) to the Roadmap and normalized the feature maturity table. Milestone rename remains parked pending sign-off.
Priority: P0
Recommended Resolution: Re-baseline phases into: Implemented, Implemented but unverified, Demo-only, Planned, Rejected.
Related ADRs: —
Related Documents: Roadmap, Gap Analysis, Implementation Plan
Related Phases: Phase 3, Phase 5
Root Cause: Roadmap is not updated transactionally with implementation.
Severity: Critical
Status: Resolved
Target Resolution Date: May 31, 2026

**Description**

Phase 5 is simultaneously “planned” and “added.” Phase 3 is both a current blocker and apparently bypassed.

**Root cause**

Roadmap is not updated transactionally with implementation.

### Impact

- **Business:** Demo claims become fragile. You cannot defend readiness under scrutiny.
- **Technical:** Teams may build Phase 7–9 features on unstable Phase 3–5 foundations.
- **Customer:** Users encounter broken consultation or analysis flows.
- **Risk if unresolved:** Project becomes a pile of partially implemented vertical slices.

**Recommended resolution**

Re-baseline phases into: **Implemented**, **Implemented but unverified**, **Demo-only**, **Planned**, **Rejected**.

**Approaches**

Also consider replacing phase numbering with release milestones: `Demo Alpha`, `Pilot MVP`, `Production Beta`. *(Not yet approved — requires sign-off before restructuring phases.)*

**Related references**

- [Roadmap — Nuvia Beauty](../../implementation-plan/Roadmap%20%E2%80%94%20Nuvia%20Beauty%20d276dacddb7e4b7bb92399a12ec3c10c.md)
- [Gap Analysis — Repo vs Notion Docs](https://app.notion.com/p/Gap-Analysis-Repo-vs-Notion-Docs-ba5bd47ca7c44bc0801097a6fb9ba242?pvs=21)
- [implementation-plan/](../../implementation-plan%20dcdd3415ed5441189d05d8599585c8ac.md)