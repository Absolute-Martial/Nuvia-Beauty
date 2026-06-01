# Phase 2 — Mapping Editors & Event Signals

Owner: Susank Shakya

<aside>
2️⃣

**Phase 2 — Mapping Editors & Event Signals** · `docs/implementation-plan/phases/phase-2/` · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21) (repo `Absolute-point/Nuvia-Beauty` · `development`). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › **Phase 2 — Mapping Editors & Event Signals**

**Navigation:** Previous: [Phase 1 — Storage & Recommendation Foundation](Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) · Next: [Phase 3 — Stabilization & Validation](Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)

This page is an overview and navigation hub. All detailed planning, tasks, deliverables, dependencies, and validation criteria live in the dedicated sub-phase pages listed below.

## Overview

Phase 2 closes the learning loop on top of the Phase 1 foundation. It adds beauty event ingestion, aggregates events into per-product signals with scheduled and on-demand recompute, and ships inline mapping editors for both platform admins and owner-scoped vendors — then updates the canonical documentation. Together these make recommendation quality maintainable and continuously improving.

## Scope

**In scope:** event ingestion, product-signal aggregation + recompute (scheduled + admin-triggered), admin inline mapping editor, vendor (owner-scoped) inline mapping editor, and documentation updates.

**Out of scope:** stabilization/validation hardening ([Phase 3 — Stabilization & Validation](Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)); consultation ([Phase 4 — Seller Consultation Foundation](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md)); provider analysis ([Phase 5 — Perfect Corp P0 Integration](Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Roadmap positioning

Phase 2 follows [Phase 1 — Storage & Recommendation Foundation](Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) and precedes the stabilization gate in [Phase 3 — Stabilization & Validation](Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md). Its owner-scoping work also lays groundwork for tenancy in [Phase 7 — Personalized Domain Expansion](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).

## Sub-phases

| Sub-phase | Focus |
| --- | --- |
| [Phase 2.1 — Beauty Event Ingestion](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%201%20%E2%80%94%20Beauty%20Event%20Ingestion%208b8f4fcd4b98471a855a939017bf669e.md) | `POST /beauty/events`; event model + validation |
| [Phase 2.2 — Product Signal Aggregation & Recompute](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%202%20%E2%80%94%20Product%20Signal%20Aggregation%20&%20Recompute%20da2e469414704aefbc52ea9959c47d77.md) | Signals table; daily + admin recompute job/command |
| [Phase 2.3 — Admin Inline Mapping Editor](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%203%20%E2%80%94%20Admin%20Inline%20Mapping%20Editor%20fdbc5fd29e92447bafacde181b00d9b8.md) | Admin-panel mapping curation UI |
| [Phase 2.4 — Vendor Inline Mapping Editor (Owner-Scoped)](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%204%20%E2%80%94%20Vendor%20Inline%20Mapping%20Editor%20(Owner-Sc%2061a0adb2edce44e2bfe73cc8e60d9d6a.md) | Vendor-portal editor with owner-scoped authorization |
| [Phase 2.5 — Documentation Updates](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%205%20%E2%80%94%20Documentation%20Updates%20274e8508693947e6b00477974afdda32.md) | API/schema/queue docs refreshed for Phase 2 |

## Phase-level exit criteria

- Events ingested and aggregated into signals with safe, scheduled recompute.
- Admin and vendor mapping editors live with correct authorization.
- Documentation updated to match implemented endpoints and workflows.

## Related documentation

- Hub: [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) · [phases/](../phases%2048c28ced7f964f33a064261f94b632ed.md) · [Phase Index — Canonical Naming & Template](Phase%20Index%20%E2%80%94%20Canonical%20Naming%20&%20Template%209ab2e9ed85a0417681d63789c64a98ad.md)
- Backend: [[api-contracts.md](http://api-contracts.md)](../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · [[database-schema.md](http://database-schema.md)](../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · [[queue-jobs.md](http://queue-jobs.md)](../../backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md)
- Prev: [Phase 1 — Storage & Recommendation Foundation](Phase%201%20%E2%80%94%20Storage%20&%20Recommendation%20Foundation%205b78c2776c604be5ad092fe899ce176b.md) · Next: [Phase 3 — Stabilization & Validation](Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)

## Sub-phase pages

[Phase 2.1 — Beauty Event Ingestion](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%201%20%E2%80%94%20Beauty%20Event%20Ingestion%208b8f4fcd4b98471a855a939017bf669e.md)

[Phase 2.4 — Vendor Inline Mapping Editor (Owner-Scoped)](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%204%20%E2%80%94%20Vendor%20Inline%20Mapping%20Editor%20(Owner-Sc%2061a0adb2edce44e2bfe73cc8e60d9d6a.md)

[Phase 2.3 — Admin Inline Mapping Editor](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%203%20%E2%80%94%20Admin%20Inline%20Mapping%20Editor%20fdbc5fd29e92447bafacde181b00d9b8.md)

[Phase 2.5 — Documentation Updates](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%205%20%E2%80%94%20Documentation%20Updates%20274e8508693947e6b00477974afdda32.md)

[Phase 2.2 — Product Signal Aggregation & Recompute](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals/Phase%202%202%20%E2%80%94%20Product%20Signal%20Aggregation%20&%20Recompute%20da2e469414704aefbc52ea9959c47d77.md)