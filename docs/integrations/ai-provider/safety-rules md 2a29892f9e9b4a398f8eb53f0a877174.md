# safety-rules.md

Owner: Susank Shakya

<aside>
🛡️

**`docs/integrations/ai-provider/safety-rules.md`** · content, media, and language safety applied to inputs and outputs.

</aside>

# 1. Purpose & scope

This page defines the **safety rules** enforced on everything entering and leaving the AI pipeline: media inputs, analysis outputs, consent, privacy, and the retail-safe language that all user-facing copy must follow. Retail-safe, non-clinical language is mandated by [ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0005.

# 2. Input safety

- **Capture-quality gate** — a MediaPipe face-landmarking check validates framing, lighting, and a single clear face before analysis; poor captures are rejected with guidance, not sent to a provider.
- **Media validation** — content-type and size are validated; only expected image types are accepted.
- **Consent gate** — no analysis runs without a recorded consent grant for the relevant tier.

# 3. Output safety

- **Confidence filtering** — low-confidence results are suppressed or surfaced with explicit `warnings[]` rather than presented as certain.
- **Language screening** — outputs are checked against the retail-safe vocabulary; clinical/medical phrasing is blocked.
- **Explainability** — every recommendation carries `reasons[]` and, where relevant, `warnings[]` (e.g. “patch test recommended”).

# 4. Retail-safe language

| Allowed (retail-safe) | Blocked (clinical/medical) |
| --- | --- |
| “appears oily / looks dry”, “may suit”, “patch test recommended” | “diagnose”, “treat”, “cure”, “medical condition” |
| “well-suited to your preferences”, “many customers like” | “clinically proven”, “dermatologist-grade therapy” |

# 5. Privacy

- Private media is never exposed publicly; results are stored in private buckets (see `storage/`).
- Raw media is never shared upward to brands (ADR 0009); only consented, structured data crosses tiers.
- Data minimization: send only what the provider needs (see `provider-boundaries.md`).

# 6. Enforcement

- Safety checks run as part of the orchestration jobs, before results are stored or surfaced.
- Violations are logged; unsafe content is **blocked rather than surfaced**.
- Repeated issues escalate per the operations runbooks (`runbooks/ai-provider-failure.md`).

# 7. Related documentation

- Orchestration: `orchestration.md`. Boundaries: `provider-boundaries.md`. Privacy/storage: `storage/private-bucket-policy.md`.
- Decisions: ADR 0005, 0009, 0012. Positioning language: `product/concept-paper.md`, `product/white-paper.md`.