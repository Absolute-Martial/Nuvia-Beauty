# Phase 9 — Try-On Studio & Makeup VTO

Owner: Susank Shakya

<aside>
9️⃣

**Phase 9 — Try-On Studio & Makeup VTO** · `docs/implementation-plan/phases/phase-9/` · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21) (repo `Absolute-point/Nuvia-Beauty` · `development`). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › **Phase 9 — Try-On Studio & Makeup VTO**

**Navigation:** Previous: [Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) · Next: — (final phase)

This page is an overview and navigation hub. All detailed planning, tasks, deliverables, dependencies, and validation criteria live in the dedicated sub-phase pages listed below.

## Overview

Phase 9 delivers the makeup virtual try-on (VTO) studio: a `makeup_vto` task type + eligibility service, provider orchestration with private result-media storage, a try-on API surface with save/discard and demo mode, the customer-facing try-on studio UI, and vendor/admin tools to manage VTO eligibility. It is the final roadmap phase.

## Scope

**In scope:** VTO task type + eligibility, provider service/jobs + result media storage, save/discard endpoints + demo mode, the try-on studio UI, and vendor/admin eligibility mapping UI.

**Out of scope (Beyond roadmap):** manufacturer feedback loop and custom analysis model + brand portal — tracked as future work after Phase 9.

## Roadmap positioning

Phase 9 builds on the analysis pipeline ([Phase 5 — Perfect Corp P0 Integration](Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)), mapping editors ([Phase 2 — Mapping Editors & Event Signals](Phase%202%20%E2%80%94%20Mapping%20Editors%20&%20Event%20Signals%20e2a84e5c65424679bfb240a2359bb061.md)), and the customer experience foundation ([Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)). It is the final phase; subsequent work is tracked as “Beyond roadmap.”

## Sub-phases

| Sub-phase | Focus |
| --- | --- |
| [Phase 9.1 — VTO Task Type & Eligibility Service](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%201%20%E2%80%94%20VTO%20Task%20Type%20&%20Eligibility%20Service%205e421e4bab0649c183a18d6efab45b09.md) | `makeup_vto` task type + eligibility |
| [Phase 9.2 — Provider Service, Jobs & Result Media Storage](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%202%20%E2%80%94%20Provider%20Service,%20Jobs%20&%20Result%20Media%20%20c0c9146e62ba4821b9c51ac24c9a6cfd.md) | VTO orchestration + private result media |
| [Phase 9.3 — Save/Discard Endpoints & Demo Mode](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%203%20%E2%80%94%20Save%20Discard%20Endpoints%20&%20Demo%20Mode%2003efdfb9c136427dafe20092951e34ee.md) | Try-on API + demo-mode fallback |
| [Phase 9.4 — Frontend Try-On UI](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%204%20%E2%80%94%20Frontend%20Try-On%20UI%2094133bcbed7341a684234866bd94eb06.md) | Customer try-on studio UI |
| [Phase 9.5 — Vendor/Admin Eligibility Mapping UI](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%205%20%E2%80%94%20Vendor%20Admin%20Eligibility%20Mapping%20UI%20b52ceec7ead34657bb5ad9db040a606a.md) | Operator eligibility + mapping management |

## Phase-level exit criteria

- Eligible products can be virtually tried on end-to-end (demo mode at minimum).
- Result media is generated + stored privately; save/discard works.
- Vendors/admins can manage VTO eligibility accurately.

## Related documentation

- Hub: [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) · [phases/](../phases%2048c28ced7f964f33a064261f94b632ed.md) · [Phase Index — Canonical Naming & Template](Phase%20Index%20%E2%80%94%20Canonical%20Naming%20&%20Template%209ab2e9ed85a0417681d63789c64a98ad.md)
- API/Schema/Jobs: [[api-contracts.md](http://api-contracts.md)](../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · [[database-schema.md](http://database-schema.md)](../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · [[queue-jobs.md](http://queue-jobs.md)](../../backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md) · Architecture: [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21)
- Prev: [Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) · Next: — (final phase)

## Sub-phase pages

[Phase 9.4 — Frontend Try-On UI](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%204%20%E2%80%94%20Frontend%20Try-On%20UI%2094133bcbed7341a684234866bd94eb06.md)

[Phase 9.1 — VTO Task Type & Eligibility Service](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%201%20%E2%80%94%20VTO%20Task%20Type%20&%20Eligibility%20Service%205e421e4bab0649c183a18d6efab45b09.md)

[Phase 9.2 — Provider Service, Jobs & Result Media Storage](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%202%20%E2%80%94%20Provider%20Service,%20Jobs%20&%20Result%20Media%20%20c0c9146e62ba4821b9c51ac24c9a6cfd.md)

[Phase 9.5 — Vendor/Admin Eligibility Mapping UI](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%205%20%E2%80%94%20Vendor%20Admin%20Eligibility%20Mapping%20UI%20b52ceec7ead34657bb5ad9db040a606a.md)

[Phase 9.3 — Save/Discard Endpoints & Demo Mode](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO/Phase%209%203%20%E2%80%94%20Save%20Discard%20Endpoints%20&%20Demo%20Mode%2003efdfb9c136427dafe20092951e34ee.md)