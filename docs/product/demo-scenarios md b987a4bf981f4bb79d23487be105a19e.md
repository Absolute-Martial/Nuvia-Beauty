# demo-scenarios.md

Owner: Susank Shakya

<aside>
🎬

**`docs/product/demo-scenarios.md`** · scripted, reliable scenarios for demos — all runnable in demo-mode (no live credentials).

</aside>

# 1. Purpose

These scenarios are the **rehearsed scripts** used to demonstrate Nuvia Beauty end-to-end. Each one is designed to run reliably in **demo-mode** — no live Perfect Corp or storage credentials required — so a demo never depends on external availability. They map directly to the MVP boundary and exercise the full confidence-shopping loop.

# 2. Pre-demo checklist

```
seed data loaded (products, mappings, sample profiles)
demo-mode enabled (provider toggled to fallback)
storefront / vendor-portal / admin-panel reachable
signed-URL flow verified (no public private media)
sample consultation profiles available for reopen
audit logging confirmed active
```

- Seed data is loaded beforehand (see the demo-readiness checklist in `operations/`).
- No private media is ever publicly reachable during a demo.

# 3. Scenarios

## Scenario 1 — Confident shopper (storefront)

**Goal:** show explainable recommendations without any scan.

```
browse catalog → view recommendations with reasons/warnings → read "Why we recommend this" → add to cart
```

**Highlights:** deterministic scoring, reasons and warnings on every card, retail-safe language.

## Scenario 2 — Consented self-scan (storefront)

**Goal:** show the consented analysis → profile → personalized recommendations loop.

```
give consent (T1) → capture/upload photo → capture-quality gate → backend analysis (demo-mode) → profile snapshot → personalized recommendations → save profile
```

**Highlights:** backend-only analysis, capture-quality gate, structured snapshot, reopenable profile.

## Scenario 3 — Seller consultation (vendor-portal)

**Goal:** show the assisted in-store consultation with seller correction.

```
start consultation → capture with consent → review AI output → correct attributes → present recommendations → save or discard
```

**Highlights:** seller review & correction (high-quality labels), session lifecycle, customer copy for later.

## Scenario 4 — Admin update (admin-panel)

**Goal:** show operator control propagating to the storefront.

```
edit a product / mapping or toggle a setting → recompute → see the effect reflected in storefront recommendations
```

**Highlights:** product mapping, provider toggles, quota, audit trail.

## Scenario 5 — Resilience (cross-app)

**Goal:** prove the system degrades gracefully.

```
simulate provider "down" / quota exhausted → demo-mode fallback engages → consultation flow still completes → failed-task visibility in admin
```

**Highlights:** multi-provider fallback chain, demo-mode fallback, failed-task visibility.

# 4. Scenario coverage map

| Scenario | App | Proves |
| --- | --- | --- |
| 1. Confident shopper | storefront | Explainable deterministic recommendations. |
| 2. Consented self-scan | storefront | Consent → analysis → profile → personalization. |
| 3. Seller consultation | vendor-portal | Assisted capture, correction, session lifecycle. |
| 4. Admin update | admin-panel | Operator control and recompute propagation. |
| 5. Resilience | cross-app | Graceful degradation and failed-task visibility. |

# 5. Related documentation

- Scope: `mvp-boundary.md`. Vision: `concept-paper.md`, `white-paper.md`.
- Readiness checklists: `operations/`. Flows: `architecture/diagrams/sequence-diagrams.md`.