# recommendation-ui.md

Owner: Susank Shakya

<aside>
✨

**`docs/storefront/recommendation-ui.md`** · how recommendations and their confidence are presented to build trust.

</aside>

# 1. Purpose & scope

This page defines how the storefront **presents recommendations** so customers understand and trust them. The UI renders the canonical recommendation payload from `backend-engine` and never exposes raw provider data or internal scores beyond it.

# 2. The payload it renders

```json
{
  "product_id": "string",
  "score": 0.0,
  "confidence": 0.0,
  "reasons": ["string"],
  "warnings": ["string"]
}
```

# 3. What is shown

| Element | Source | Presentation |
| --- | --- | --- |
| Ranked products | `score` | Ordered list/grid, highest first. |
| Confidence indicator | `confidence` | High / medium / low badge. |
| Reasons | `reasons[]` | Short, human-readable chips/lines. |
| Warnings | `warnings[]` | Caveats (e.g. “patch test recommended”, low confidence). |

# 4. Behavior

- Low-confidence items are **clearly labeled rather than hidden**, preserving honesty and trust.
- When analysis is unavailable, the UI shows **baseline recommendations** and indicates that personalization is off.
- The UI never exposes raw provider data or internal scores beyond the canonical payload.
- Copy is retail-safe; warnings use approachable, non-clinical language (ADR 0005).

# 5. Accessibility & i18n

- Confidence is conveyed by label and shape, not color alone.
- All strings are localized via next-i18next / i18next.

# 6. Related documentation

- Payload source: `backend-engine/api-contracts.md`. Scoring: `integrations/ai-provider/orchestration.md`. Safety language: `integrations/ai-provider/safety-rules.md`.