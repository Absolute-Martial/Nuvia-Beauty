# integrations/

Owner: Susank Shakya

<aside>
📁

**`docs/integrations/`** — external integrations and the boundaries that keep them backend-only, swappable, and safe.

</aside>

This folder documents how Nuvia Beauty integrates external AI analysis. It is split into a **provider-agnostic** layer (orchestration, boundaries, safety) and the **Perfect Corp P0** integration that cold-starts analysis today. Every integration is backend-only ([ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0002), normalizes output into a stable internal contract, and degrades to deterministic demo-mode so flows never break.

# Contents

| Subfolder | Covers |
| --- | --- |
| [ai-provider/](integrations/ai-provider%20ba68fdfd594743d194166dba8b3a19a0.md) | Orchestration pipeline, provider boundaries, and safety rules (provider-agnostic). |
| [perfect-corp/](integrations/perfect-corp%20e0ebfd9fd40d47ec82cb8cb92b46e264.md) | P0 analysis API, demo-mode, request/response contract, and fallback behavior. |

# How to read this folder

1. Start with `ai-provider/orchestration.md` for the end-to-end pipeline.
2. Read `ai-provider/provider-boundaries.md` and `ai-provider/safety-rules.md` for the rules that constrain it.
3. Read the `perfect-corp/` pages for the concrete P0 provider, its contract, and its fallback.

The future custom model (ADR 0008) will plug into the same provider interface described here.

[ai-provider/](integrations/ai-provider%20ba68fdfd594743d194166dba8b3a19a0.md)

[perfect-corp/](integrations/perfect-corp%20e0ebfd9fd40d47ec82cb8cb92b46e264.md)