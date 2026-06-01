# Phase 8 — Customer Self-Scan & Profile History

Owner: Susank Shakya

<aside>
8️⃣

**Phase 8 — Customer Self-Scan & Profile History** · `docs/implementation-plan/phases/phase-8/` · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21) (repo `Absolute-point/Nuvia-Beauty` · `development`). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › **Phase 8 — Customer Self-Scan & Profile History**

**Navigation:** Previous: [Phase 7 — Personalized Domain Expansion](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) · Next: [Phase 9 — Try-On Studio & Makeup VTO](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)

This page is an overview and navigation hub. All detailed planning, tasks, deliverables, dependencies, and validation criteria live in the dedicated sub-phase pages listed below.

## Overview

Phase 8 brings beauty intelligence directly to customers via self-service scanning and a personal profile history. It adds a quota- and policy-gated self-scan backend, a media quality gate with privacy/retention, snapshot timeline + comparison services, a complete customer API surface, a mobile PWA scan flow, and a history/comparison UI with privacy controls.

## Scope

**In scope:** self-scan backend, media quality gate + privacy/retention, snapshot timeline/comparison services, customer self-scan APIs, customer PWA scan flow, and profile history/comparison UI + privacy controls.

**Out of scope:** try-on/VTO ([Phase 9 — Try-On Studio & Makeup VTO](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)); multi-tenant foundation (delivered in [Phase 7 — Personalized Domain Expansion](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)).

## Roadmap positioning

Phase 8 builds on the multi-tenant foundation ([Phase 7 — Personalized Domain Expansion](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)) and the analysis pipeline ([Phase 5 — Perfect Corp P0 Integration](Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)), and precedes the try-on studio ([Phase 9 — Try-On Studio & Makeup VTO](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)).

## Sub-phases

| Sub-phase | Focus |
| --- | --- |
| [Phase 8.1 — Customer Self-Scan Backend](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%201%20%E2%80%94%20Customer%20Self-Scan%20Backend%20f07c3f3de7df4106b5d5470754c9d284.md) | Customer-initiated scans + quota/policy |
| [Phase 8.2 — Quality Gate & Media Privacy/Retention](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%202%20%E2%80%94%20Quality%20Gate%20&%20Media%20Privacy%20Retention%207edbb6378b16452bbab084f5853f51ea.md) | Media quality + private storage + retention |
| [Phase 8.3 — Snapshot Timeline & Comparison Services](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%203%20%E2%80%94%20Snapshot%20Timeline%20&%20Comparison%20Service%201989c61d469f4fb39cdae26236f791b9.md) | Timeline + snapshot deltas |
| [Phase 8.4 — Customer Self-Scan APIs](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%204%20%E2%80%94%20Customer%20Self-Scan%20APIs%20587a43c6317d46f3b8a51cb05477020d.md) | Scan/media/analysis/history/deletion endpoints |
| [Phase 8.5 — Customer PWA Scan Flow](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%205%20%E2%80%94%20Customer%20PWA%20Scan%20Flow%20306f531626ad45aa9fdbaafedf58d34c.md) | Mobile capture → analysis → results |
| [Phase 8.6 — Profile History/Comparison UI & Privacy Controls](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%206%20%E2%80%94%20Profile%20History%20Comparison%20UI%20&%20Privac%20c756c048ca8f46bdb5da56f55c465f48.md) | History/compare UI + consent/deletion |

## Phase-level exit criteria

- Customers can self-scan within quota and see enriched results.
- Media is quality-gated, private, and retention-governed.
- Profile history, comparisons, and privacy controls are available end-to-end.

## Related documentation

- Hub: [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) · [phases/](../phases%2048c28ced7f964f33a064261f94b632ed.md) · [Phase Index — Canonical Naming & Template](Phase%20Index%20%E2%80%94%20Canonical%20Naming%20&%20Template%209ab2e9ed85a0417681d63789c64a98ad.md)
- API/Schema: [[api-contracts.md](http://api-contracts.md)](../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · [[database-schema.md](http://database-schema.md)](../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · Storage: [PoC 12 — Storage & Beauty Intelligence Foundation (Phase 4)](https://app.notion.com/p/PoC-12-Storage-Beauty-Intelligence-Foundation-Phase-4-0b6ab14688844db88c69148cff827930?pvs=21) · Security: [Request for Comments (RFC)](https://app.notion.com/p/Request-for-Comments-RFC-411c875deb6e4bb79489bf2e536e41cc?pvs=21)
- Prev: [Phase 7 — Personalized Domain Expansion](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) · Next: [Phase 9 — Try-On Studio & Makeup VTO](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)

## Sub-phase pages

[Phase 8.3 — Snapshot Timeline & Comparison Services](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%203%20%E2%80%94%20Snapshot%20Timeline%20&%20Comparison%20Service%201989c61d469f4fb39cdae26236f791b9.md)

[Phase 8.1 — Customer Self-Scan Backend](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%201%20%E2%80%94%20Customer%20Self-Scan%20Backend%20f07c3f3de7df4106b5d5470754c9d284.md)

[Phase 8.6 — Profile History/Comparison UI & Privacy Controls](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%206%20%E2%80%94%20Profile%20History%20Comparison%20UI%20&%20Privac%20c756c048ca8f46bdb5da56f55c465f48.md)

[Phase 8.2 — Quality Gate & Media Privacy/Retention](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%202%20%E2%80%94%20Quality%20Gate%20&%20Media%20Privacy%20Retention%207edbb6378b16452bbab084f5853f51ea.md)

[Phase 8.5 — Customer PWA Scan Flow](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%205%20%E2%80%94%20Customer%20PWA%20Scan%20Flow%20306f531626ad45aa9fdbaafedf58d34c.md)

[Phase 8.4 — Customer Self-Scan APIs](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History/Phase%208%204%20%E2%80%94%20Customer%20Self-Scan%20APIs%20587a43c6317d46f3b8a51cb05477020d.md)