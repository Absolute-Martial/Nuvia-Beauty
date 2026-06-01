# Feature Brainstorming — Nuvia Beauty

Owner: Susank Shakya

<aside>
💡

This is the feature brainstorming workspace for Nuvia Beauty / MatchMuse Beauty. Keep raw ideas here before promoting them into phase plans or architecture docs.

</aside>

## Feature buckets

- Customer confidence
- Seller consultation
- Product mapping
- AI analysis
- Recommendation engine
- Personalization
- Try-On Studio
- Admin operations
- Safety and privacy
- Analytics and insights

## MVP shortlist

| Feature | User | Why it matters | Phase | Priority |
| --- | --- | --- | --- | --- |
| Private media storage | Customer / Platform | Protects sensitive scan inputs and keeps storage auditable. | Phase 1–3 | Must-have |
| Beauty product mapping | Admin / Vendor | Turns products into recommendation-ready items. | Phase 1–3 | Must-have |
| Deterministic recommendation engine | Customer / Seller | Produces explainable product matches without opaque ranking. | Phase 1–3 | Must-have |
| Recommendation explanation card | Customer / Seller | Builds confidence by explaining why a product matches. | Phase 3–4 | Must-have |
| Seller consultation mode | Seller | Supports physical retailers and assisted selling. | Phase 4 | Should-have |
| Perfect Corp P0 analysis | Customer / Platform | Adds controlled AI skin/tone analysis. | Phase 5 | Should-have |
| Customer profile history | Customer / Seller | Supports repeat visits and reusable recommendations. | Phase 8 | Later |
| Try-On Studio | Customer | Adds visual purchase confidence after analysis is stable. | Phase 9 | Later |

## Customer-facing ideas

### Beauty profile builder

Capture skin type, tone, undertone, concerns, sensitivities, preferred brands, budget, and shopping goal.

### Confidence recommendation card

Show recommended product, score, confidence, reasons, warnings, alternatives, and seller note.

### “Why this product?” explanation

Translate recommendation signals into readable language:

- Matches oily skin profile.
- Suitable for warm undertone.
- Supports dark spot concern.
- Avoid if fragrance-sensitive.

### Routine builder

Generate morning, evening, event, beginner, acne-safe, or budget routines.

### Product comparison

Compare products by skin match, concern match, ingredients, price, availability, seller recommendation, and warnings.

### Profile history

Show previous scans, recommendations, saved products, purchased products, and consultation notes.

### Try-On Studio

Later-stage virtual try-on for lipstick, foundation shade preview, blush, eye shadow, and basic makeup looks.

## Seller-facing ideas

### Guided consultation mode

A seller-guided questionnaire for customer goal, skin type, sensitivity, budget, brand preference, and desired look.

### Seller recommendation workspace

Show profile summary, recommendations, reasons, alternatives, inventory availability, and notes.

### Consultation history

Track previous visits, recommended products, purchases, rejected products, and preferences.

### Product mapping editor

Support skin type suitability, concern suitability, tone/undertone support, product category, ingredient flags, usage notes, and warnings.

### Seller scripts

Generate explainable talking points for customer conversations.

## Admin-facing ideas

### Beauty intelligence dashboard

Track mapped products, unmapped products, low-confidence recommendations, failed provider calls, storage health, media cleanup, top concerns, and most recommended products.

### Mapping quality score

Score product mapping completeness and show missing attributes.

### Recommendation audit log

Record request source, profile used, product recommendations, provider used, explanation, and seller override.

### Provider monitoring

Track provider status, quota, latency, error rate, fallback mode, and manual override.

### Safety review panel

Review medical-claim language, risky warnings, sensitive-skin outputs, low-confidence AI results, and seller override patterns.

## Backend/platform ideas

### Recommendation engine v1

Score against product mapping, skin type, concern, tone/undertone, ingredient warnings, preferences, and seller context.

### Provider orchestration layer

Keep Perfect Corp and future provider integrations backend-only.

### Media lifecycle service

Support temporary uploads, private object storage, signed URLs, expiry, deletion queue, audit logs, and cleanup jobs.

### Event signal system

Capture product viewed, product saved, product recommended, product purchased, seller override, rejected recommendation, and liked recommendation.

### Explainability service

Separate scoring, explanation generation, and safety filtering.

## Prioritization rules

- Must-have: required for MVP confidence shopping.
- Should-have: improves assisted selling or trust but can follow MVP.
- Later: valuable after foundation and validation.
- Experimental: useful for differentiation but not needed for proof.

## Rejected / out-of-scope for now

- Clinical or medical diagnosis.
- Beauty filter as the core product.
- Frontend provider credentials.
- Raw image bytes in MySQL.
- Hair, jewelry, or clothing VTO before makeup VTO is stable.
- Multi-provider chaining before Perfect Corp P0 is stable.