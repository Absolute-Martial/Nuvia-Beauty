# try-on-studio.md

Owner: Susank Shakya

<aside>
💄

**`docs/storefront/try-on-studio.md`** · the virtual try-on (VTO) experience — preview products before buying.

</aside>

# 1. Purpose & scope

The Try-On Studio lets customers **preview products** (e.g. makeup shades) on a captured or sample image before buying. It is the most advanced storefront surface and is delivered later in the roadmap (Phase 9 — Try-On Studio & Makeup VTO). Earlier phases ship a graceful placeholder so the surface is always present.

# 2. Experience

- Select a product/shade and preview it on a captured or sample image.
- Try-on rendering is **provider-assisted** and runs through `backend-engine`; the frontend never calls providers directly.
- Results respect the same consent and private-media rules as self-scan.
- Customers can compare shades and add the chosen product to cart from the studio.

# 3. Boundaries

- **Backend-only providers** — all VTO rendering calls are orchestrated server-side (ADR 0002).
- **Consent + private media** — any captured image follows the self-scan consent and storage rules.
- **Retail-safe** — previews are presented as visual aids, never as guarantees or clinical claims (ADR 0005).

# 4. Status & fallback

- **Phase 9** capability; earlier phases ship a graceful placeholder.
- Falls back to a non-personalized preview (sample image / static swatch) when provider/VTO is unavailable.

# 5. Related documentation

- Roadmap: `product/technical-proposal.md` (Phase 9), `product/mvp-boundary.md` (out of MVP scope).
- Capture & media: `self-scan-flow.md`, `storage/media-lifecycle.md`. Orchestration: `integrations/ai-provider/orchestration.md`.