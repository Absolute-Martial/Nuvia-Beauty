# demo-scenarios.md

Owner: Susank Shakya

<aside>
🎬

**`docs/product/demo-scenarios.md`** · scripted, demo-mode-ready scenarios that only reference features implemented through Phase 5.

</aside>

# 1. Purpose

This page is the Phase 6 presenter script. It turns the seeded Phase 4–5 feature set into a reliable walkthrough across storefront, vendor portal, and admin without depending on live Perfect Corp credentials.

# 2. Pre-flight

Run the backend demo-prep script first:

```bash
bash scripts/phase6-demo-readiness.sh
```

Expected results:

- `backend-engine/storage/app/beauty/phase6-demo-preparation-report.json` is created.
- `backend-engine/storage/app/beauty/phase6-demo-audit-report.json` reports `passed: true`.
- `PERFECT_CORP_DEMO_MODE=true`.
- the preparation report lists `catalog_sources` for the public beauty datasets used to curate the demo assortment.
- frontend apps are reachable on their configured demo URLs.

Fallback:

- If the script fails because app services are stale, restart the dev/demo stack and rerun the script.
- If object-storage env vars are intentionally absent in testing, rely on the audit report and the backend test evidence rather than attempting live private-media uploads.

# 3. Demo timeline

Target length: 6-8 minutes.

## Segment 1 — Storefront explainable recommendations (90 seconds)

**Surface:** storefront

**Action**

1. Open the seeded demo catalog product list.
2. Open a product that has beauty recommendations enabled.
3. Show the recommendation cards with reasons, warnings, and score badges.

**Expected result**

- Reasons are human-readable and retail-safe.
- At least one recommendation shows an avoid warning example.
- Recommendation scores are deterministic and do not depend on the provider.

**Talking points**

- “We can explain why a product appears, not just rank it.”
- “The warning path is deliberate; the system does not hide contraindication-style avoid tags.”
- “The seeded assortment is curated from public beauty dataset samples, so the catalog looks like something a user could plausibly test.”

## Segment 2 — Seller consultation with demo-mode analysis (3 minutes)

**Surface:** vendor portal

**Action**

1. Open `/beauty/consultations`.
2. Start a consultation for a guest customer profile.
3. Attach private consultation media or use the prepared demo state as fallback.
4. Start analysis.
5. Wait for the status panel to move through queued/processing/completed.
6. Show the normalized analysis summary and refreshed recommendations.
7. Save the consultation.

**Expected result**

- The task provider is Perfect Corp P0 in demo mode.
- The analysis summary is normalized; no raw provider payload is shown.
- Recommendations refresh after analysis completion.
- The consultation can be saved or discarded cleanly.

**Talking points**

- “The browser never receives provider secrets.”
- “Demo mode exercises the same orchestration path as live mode, but with deterministic output.”
- “The seller can still intervene before finalizing the recommendation set.”

**Fallback**

- If live media upload is not available in the current environment, open the prepared demo reports and explain that the seeded consultation already completed the same backend path.

## Segment 3 — Admin mapping overview and recompute controls (90 seconds)

**Surface:** admin panel

**Action**

1. Open `/products/beauty-mappings`.
2. Show mapped, unmapped, partial, and ready counts.
3. Open a product mapping and explain the attribute tags.
4. Trigger recompute for one mapped product or all mapped products.

**Expected result**

- Admin sees mapping status for the current catalog.
- Recompute is restricted to authorized admin actors.
- Mapping edits affect the recommendation layer, not frontend-only state.

**Talking points**

- “Operators can fix recommendation quality without redeploying the storefront.”
- “The same backend scoring service is reused after recompute.”

## Segment 4 — Demo readiness proof (60 seconds)

**Surface:** terminal + docs

**Action**

1. Show the generated preparation and audit reports.
2. Reference the evidence docs under `docs/implementation-plan/evidence/`.
3. Show that backend and frontend verification already passed before the demo.

**Expected result**

- Demo claims are backed by command output and stored evidence.
- The demo can be reproduced by another presenter from the script.

# 4. Audience scenarios

## Investor / judge

- Emphasize explainable conversion support, demo reliability, and readiness discipline.
- Focus on Segments 1, 2, and 4.
- Likely question: “What still needs to happen before production?”
  Answer: “Phase 6 is demo hardening; tenancy, self-scan history, and VTO remain later roadmap phases.”

## Retail operator

- Emphasize seller-assisted consultation, admin mapping control, and warning visibility.
- Focus on Segments 2 and 3.
- Likely question: “Can store staff override weak AI output?”
  Answer: “Yes. The seller can review the analysis result, edit mapping inputs, and decide whether to save or discard the consultation.”

## Technical reviewer

- Emphasize backend-only provider integration, private media handling, deterministic scoring, and evidence-backed validation.
- Focus on Segments 2, 3, and 4.
- Likely question: “What happens if the provider is unavailable?”
  Answer: “The Phase 5 demo path uses deterministic demo mode. Live mode remains explicitly gated by backend env vars and readiness checks.”

# 5. What not to demo

- Customer self-scan history and profile comparison: Phase 8, not current.
- Makeup try-on / VTO: Phase 9, not current.
- Live Perfect Corp credentials or any raw provider payload.

# 6. Related documentation

- Data prep: `scripts/phase6-demo-readiness.sh`
- Evidence: `docs/implementation-plan/evidence/`
- Readiness checks: `docs/implementation-plan/checklists/demo-readiness.md`
