# Phase 4 — Seller Consultation Foundation

Owner: Susank Shakya

<aside>
4️⃣

**Phase 4 — Seller Consultation Foundation** · `docs/implementation-plan/phases/phase-4/` · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21) (repo `Absolute-point/Nuvia-Beauty` · `development`). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › **Phase 4 — Seller Consultation Foundation**

**Navigation:** Previous: [Phase 3 — Stabilization & Validation](Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md) · Next: [Phase 5 — Perfect Corp P0 Integration](Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)

This page is an overview and navigation hub. All detailed planning, tasks, deliverables, dependencies, and validation criteria live in the dedicated sub-phase pages listed below.

## Overview

Phase 4 builds the seller-led consultation foundation. It defines the consultation data model (sessions, profiles, snapshots, tasks, results, quota, audit), enforces a strict session state machine, exposes the session APIs, ships the vendor consultation UI, and finalizes save/discard semantics with a full audit trail. This is the backbone the provider-analysis, self-scan, and try-on phases build on.

## Scope

**In scope:** consultation schema, session lifecycle/state machine, session APIs, vendor consultation UI, and save/discard + audit.

**Out of scope:** provider analysis integration ([Phase 5 — Perfect Corp P0 Integration](Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)); customer self-scan/history ([Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)); try-on ([Phase 9 — Try-On Studio & Makeup VTO](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)).

## Roadmap positioning

Phase 4 follows the stabilization gate ([Phase 3 — Stabilization & Validation](Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)) and precedes provider analysis ([Phase 5 — Perfect Corp P0 Integration](Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)). Its schema and state machine are reused by [Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md) and [Phase 9 — Try-On Studio & Makeup VTO](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md).

## Sub-phases

| Sub-phase | Focus |
| --- | --- |
| [Phase 4.1 — Consultation Data Model](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%201%20%E2%80%94%20Consultation%20Data%20Model%2098048ab55da14a89922e86f7333d0013.md) | Sessions/profiles/snapshots/tasks/results/quota/audit schema |
| [Phase 4.2 — Session Lifecycle & State Machine](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%202%20%E2%80%94%20Session%20Lifecycle%20&%20State%20Machine%20d6af1f9c924a4bd3994e7ebb330384ab.md) | Guarded `draft→…→saved/discarded/failed` transitions |
| [Phase 4.3 — Session APIs](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%203%20%E2%80%94%20Session%20APIs%208119315cdac84d26aa2e2c5d1636d294.md) | Create/read/attach-media/recommendations/save/discard |
| [Phase 4.4 — Vendor Consultation UI](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%204%20%E2%80%94%20Vendor%20Consultation%20UI%2061e75da0aef249179e2f443e827f909e.md) | In-store consultation workflow in vendor portal |
| [Phase 4.5 — Save-Discard & Audit](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%205%20%E2%80%94%20Save-Discard%20&%20Audit%206e48321e55f549e8830934d2eceba5a4.md) | Snapshot persistence, cleanup, and audit trail |

## Phase-level exit criteria

- Consultation schema migrates cleanly with verified relationships.
- Session state machine + APIs enforce valid lifecycles.
- Vendor UI completes a full consultation; save/discard is audited.

## Related documentation

- Hub: [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) · [phases/](../phases%2048c28ced7f964f33a064261f94b632ed.md) · [Phase Index — Canonical Naming & Template](Phase%20Index%20%E2%80%94%20Canonical%20Naming%20&%20Template%209ab2e9ed85a0417681d63789c64a98ad.md)
- Backend/Arch: [[api-contracts.md](http://api-contracts.md)](../../backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) · [[database-schema.md](http://database-schema.md)](../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21) · [Request for Comments (RFC)](https://app.notion.com/p/Request-for-Comments-RFC-411c875deb6e4bb79489bf2e536e41cc?pvs=21)
- Prev: [Phase 3 — Stabilization & Validation](Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md) · Next: [Phase 5 — Perfect Corp P0 Integration](Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)

## Sub-phase pages

[Phase 4.4 — Vendor Consultation UI](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%204%20%E2%80%94%20Vendor%20Consultation%20UI%2061e75da0aef249179e2f443e827f909e.md)

[Phase 4.2 — Session Lifecycle & State Machine](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%202%20%E2%80%94%20Session%20Lifecycle%20&%20State%20Machine%20d6af1f9c924a4bd3994e7ebb330384ab.md)

[Phase 4.1 — Consultation Data Model](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%201%20%E2%80%94%20Consultation%20Data%20Model%2098048ab55da14a89922e86f7333d0013.md)

[Phase 4.3 — Session APIs](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%203%20%E2%80%94%20Session%20APIs%208119315cdac84d26aa2e2c5d1636d294.md)

[Phase 4.5 — Save-Discard & Audit](Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation/Phase%204%205%20%E2%80%94%20Save-Discard%20&%20Audit%206e48321e55f549e8830934d2eceba5a4.md)