# Roadmap — Nuvia Beauty

Owner: Susank Shakya

<aside>
🧭

This page is the canonical roadmap for Nuvia Beauty implementation planning. It separates product phases from feature brainstorming and validation work. Phase numbers are single-digit integers (Phase 0–9) and sequential from the true beginning (Phase 0), matching the Reference Docs repo.

**Current phase: Phase 3 — Stabilization & Validation, which is in progress and NOT yet complete.** Phases 4–9 are planned forward work and are not started until Phase 3 exit criteria are met. A demo-first Phase 5 (Perfect Corp P0) scaffold exists in the repo ahead of sequence, but it is unvalidated and does not change the active phase.

</aside>

## Roadmap principles

- Keep the recommendation engine usable without Perfect Corp; external AI analysis should enhance, not replace, deterministic recommendations.
- Keep AI/provider orchestration backend-only.
- Keep customer media private, short-lived where possible, auditable, and outside MySQL raw byte storage.
- Keep medical or clinical claims out of product recommendations.
- Validate each phase before expanding scope.

## Phase roadmap

| Phase | Name | Goal | Main output |
| --- | --- | --- | --- |
| Phase 0 | Baseline Verification | Verify environment, repo, migrations, and baseline application health before building beauty features. | Validated baseline |
| Phase 1 | Storage & Recommendation Foundation | Build private storage, beauty media metadata, product mapping tables, and the deterministic recommendation foundation. | Working backend foundation |
| Phase 2 | Mapping Editors & Event Signals | Let admin/vendor users manage beauty mappings and start collecting product interaction signals. | Product mapping workflow |
| Phase 3 | Stabilization & Validation | Clean up APIs, run storage validation, test recommendation behavior, and improve documentation. | Stable MVP base |
| Phase 4 | Seller Consultation Foundation | Give sellers a guided consultation workflow for recommending products. | Seller-assisted consultation flow |
| Phase 5 | Perfect Corp P0 Integration | Add backend-only provider orchestration for initial AI skin/tone analysis. | Controlled AI analysis pipeline |
| Phase 6 | Demo & Push Readiness | Prepare demo flows, seed data, QA evidence, deployment notes, and presentation scenarios. | Demo-ready build |
| Phase 7 | Personalized Domain Expansion | Add the shop-specific domain, routing, branding, and branded profile-reopen foundation so each shop feels personalized. | Shop-branded reopen experience |
| Phase 8 | Customer Self-Scan & Profile History | Let customers scan, save snapshots, compare history, and reuse profiles. | Customer beauty profile timeline |
| Phase 9 | Try-On Studio / Makeup VTO | Add controlled makeup virtual try-on only after analysis, storage, and profile history are stable. | Visual confidence layer |

## Execution order

1. Complete Phase 3 stabilization and validation.
2. Prepare real demo products and mappings.
3. Deploy to staging/test.
4. Build seller consultation foundation (Phase 4).
5. Integrate Perfect Corp P0 in controlled/demo mode (Phase 5).
6. Harden demo and push readiness (Phase 6).
7. Expand personalized domain expansion (Phase 7).
8. Add customer self-scan and profile history (Phase 8).
9. Add controlled Try-On Studio / Makeup VTO (Phase 9).

## Status taxonomy

Every feature and phase carries exactly one status so readers can audit readiness:

- **Implemented** — built and validated.
- **Implemented (unverified)** — code exists but is not yet validated or tested.
- **Demo-only** — works in demo or seeded mode; not production-grade.
- **Planned** — not started; scheduled forward work.
- **Rejected** — considered and explicitly dropped.

## Feature maturity table

| Feature | Status | Phase | Risk | Notes |
| --- | --- | --- | --- | --- |
| Private media storage | Implemented (unverified) | Phase 1–3 | Medium | Needs continued cleanup and audit validation. |
| Product mapping | Implemented (unverified) | Phase 2–3 | Medium | Add mapping completeness score later. |
| Deterministic recommendations | Implemented (unverified) | Phase 1–3 | Medium | Should remain usable without external AI provider dependency. |
| Seller consultation | Planned | Phase 4 | Medium | Depends on stable profile and recommendation explanation. |
| Perfect Corp P0 | Demo-only (scaffold in repo, unvalidated) | Phase 5 | High | Provider orchestration must stay backend-only. A demo-first scaffold (analysis/start, analysis/{taskId}/status, normalized storage, snapshot regeneration) landed early in the repo, but it is not validated; Phase 5 is not active until Phase 3 and Phase 4 complete. |
| Customer profile history | Planned | Phase 8 | Medium | Depends on consent, storage, and audit model. |
| Try-On Studio | Planned | Phase 9 | High | Only after scan/profile flow is stable. |

## Exit criteria before moving beyond Phase 3

- Backend, frontend, storage, and recommendation flows pass manual validation.
- Demo products and beauty mappings are seeded.
- Current/planned behavior is labeled clearly in docs.
- Safety rules are reflected in API, storage, frontend, and provider docs.
- Known gaps are documented in the risk register.