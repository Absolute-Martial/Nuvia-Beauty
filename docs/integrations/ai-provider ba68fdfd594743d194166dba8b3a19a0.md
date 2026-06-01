# ai-provider/

Owner: Susank Shakya

<aside>
📁

**`docs/integrations/ai-provider/`** — how `backend-engine` orchestrates AI analysis, the boundaries that isolate providers, and the safety rules applied to inputs and outputs.

</aside>

This subfolder is provider-agnostic: it describes the orchestration pipeline, the adapter boundaries that keep providers swappable and secrets server-side, and the safety/consent/language rules that gate the pipeline. Perfect Corp specifics live in the sibling `perfect-corp/` subfolder. All provider work is backend-only ([ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0002) and recommendations are deterministic first (ADR 0004).

# Contents

| Document | Covers |
| --- | --- |
| [[orchestration.md](http://orchestration.md)](ai-provider/orchestration%20md%20db9db33fe1aa4eaba0934cb5c208afb3.md) | The validate → dispatch → store → regenerate pipeline, queueing, retries, and provider strategy. |
| [[provider-boundaries.md](http://provider-boundaries.md)](ai-provider/provider-boundaries%20md%203a36f0703dab4ce0a752b25cc4250889.md) | Backend-only calls, adapter isolation, data minimization, and the boundary table. |
| [[safety-rules.md](http://safety-rules.md)](ai-provider/safety-rules%20md%202a29892f9e9b4a398f8eb53f0a877174.md) | Capture-quality gate, output filtering, consent gate, and retail-safe language. |

[[orchestration.md](http://orchestration.md)](ai-provider/orchestration%20md%20db9db33fe1aa4eaba0934cb5c208afb3.md)

[[provider-boundaries.md](http://provider-boundaries.md)](ai-provider/provider-boundaries%20md%203a36f0703dab4ce0a752b25cc4250889.md)

[[safety-rules.md](http://safety-rules.md)](ai-provider/safety-rules%20md%202a29892f9e9b4a398f8eb53f0a877174.md)