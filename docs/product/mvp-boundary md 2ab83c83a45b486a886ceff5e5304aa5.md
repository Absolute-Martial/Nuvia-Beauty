# mvp-boundary.md

Owner: Susank Shakya

<aside>
🚧

**`docs/product/mvp-boundary.md`** · what is in and out of scope for the MVP, so delivery stays focused and demoable.

</aside>

# 1. Purpose

The MVP boundary is the contract that keeps delivery focused on a single, provable outcome: an **end-to-end, demoable confidence-shopping loop** that runs without live credentials. It draws a hard line between what ships now and what is deferred to later phases, so scope creep does not jeopardize a working demo. The authoritative phase sequencing lives in the [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21) and the `implementation-plan/` folder.

# 2. MVP goal

Deliver the full loop — **consultation → analysis → structured profile → deterministic recommendations with reasons and warnings → save/reopen** — across the storefront, vendor-portal, and admin-panel, backed by the Laravel backend-engine, with private media and full audit logging.

# 3. In scope (MVP)

| Capability | What ships |
| --- | --- |
| Catalog & recommendations | Catalog browsing plus confidence-driven, deterministic recommendations with reasons and warnings. |
| Consented analysis | Self-scan / seller capture → backend analysis (P0 via Perfect Corp **or demo-mode**) with a capture-quality gate. |
| Seller consultation | Session lifecycle (draft / completed / discarded), review & correction, product mapping in the vendor portal. |
| Profile & history | Structured profile snapshots, saved profile, and reopen links (T1 consent). |
| Admin | Catalog management, settings, provider toggles, quota, mappings, and audit views. |
| Consent | Tiered, logged, revocable consent capture (T0–T4 plumbing; defaults T0/T1). |
| Media & security | Private media via signed URLs, no raw bytes in MySQL, full audit logging. |

# 4. Out of scope (later phases)

| Deferred capability | Target phase |
| --- | --- |
| Personalized shop-branded domains | Phase 7 |
| Customer self-scan + deep profile history | Phase 8 |
| Full Try-On / Makeup VTO | Phase 9 |
| Manufacturer feedback loop, brand portal, custom analysis model in production | Beyond roadmap |
| Multi-provider AI in production, Rust microservices (ADR 0013), vector search, gamification/social | Later |

# 5. Guiding principles

- **Demo-mode first.** Everything must run end-to-end in demo-mode without live credentials; the live provider is an enhancement, not a dependency.
- **Deterministic before opaque.** Recommendations stay rule-based and explainable in the MVP (ADR 0004).
- **Consent early.** Consent plumbing ships in the MVP even though brand-sharing surfaces arrive later.
- **Security non-negotiable.** No credentials in the frontend, no public private-media URLs, no raw image bytes in MySQL.
- **No medical claims.** Retail-safe language only (ADR 0005).

# 6. MVP acceptance criteria

```
seller can start a consultation
seller can attach private media
backend can run demo or live analysis
profile snapshot is created
recommendations show reasons and warnings
seller can save or discard a session
customer can reopen a saved profile later
customer can set and revoke consent tiers
no data is shared beyond the granted consent tier
no raw media is shared upward to brands
no credentials appear in the frontend
no raw media is public
```

# 7. Out-of-boundary risks & mitigations

| Risk | Mitigation |
| --- | --- |
| Scope creep into later-phase features | Treat this boundary as the gate for the demo milestone; defer non-MVP work to the implementation plan. |
| Live provider instability during a demo | Demo-mode fallback keeps the full flow working without credentials. |
| Consent debt if added late | Ship consent plumbing in the MVP even before brand surfaces exist. |

# 8. Related documentation

- Phasing & deliverables: [Technical Proposal](https://app.notion.com/p/Technical-Proposal-36ff29d2a6b181518e25d540447d7534?pvs=21) and `implementation-plan/`.
- Demo execution: `demo-scenarios.md`.
- Concept & vision: `concept-paper.md`, `white-paper.md`.
- Canonical scope authority: [](Untitled%2089b0720ce5d2457cb678cc48369d8047.md) (`docs/product/release-strategy.md`).