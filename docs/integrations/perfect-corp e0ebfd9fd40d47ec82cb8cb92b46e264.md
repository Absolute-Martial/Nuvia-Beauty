# perfect-corp/

Owner: Susank Shakya

<aside>
📁

**`docs/integrations/perfect-corp/`** — the Perfect Corp P0 analysis integration: API, deterministic demo-mode, internal contract, and fallback behavior.

</aside>

Perfect Corp provides the **P0 beauty analysis** that cold-starts Nuvia Beauty's intelligence before a custom model exists ([ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0008). The integration is backend-only, always normalizes output into the internal contract, and degrades to demo-mode so flows never break. Provider-agnostic orchestration lives in the sibling `ai-provider/` subfolder.

# Contents

| Document | Covers |
| --- | --- |
| [[README.md](http://README.md)](perfect-corp/README%20md%202c4b321b8c68460881bc853e88b4bc3f.md) | Integration overview, role, principles, and configuration. |
| [[p0-analysis-api.md](http://p0-analysis-api.md)](perfect-corp/p0-analysis-api%20md%20ef96bde7ad6745f7a4f37ead58a45ebb.md) | The P0 analysis capability, auth, input/output, and limits. |
| [[demo-mode.md](http://demo-mode.md)](perfect-corp/demo-mode%20md%2011d75fb30c1340a1b99b1ca1a4c7767f.md) | The deterministic fallback that runs without live credentials. |
| [[request-response-contract.md](http://request-response-contract.md)](perfect-corp/request-response-contract%20md%20aae7b3a57be047519c66ab929f54f5d5.md) | The stable internal request/response shape. |
| [[fallback-behavior.md](http://fallback-behavior.md)](perfect-corp/fallback-behavior%20md%20aef70c05e4544f689beb494f84452d69.md) | Triggers, fallback chain, rules, and monitoring. |

[[README.md](http://README.md)](perfect-corp/README%20md%202c4b321b8c68460881bc853e88b4bc3f.md)

[[p0-analysis-api.md](http://p0-analysis-api.md)](perfect-corp/p0-analysis-api%20md%20ef96bde7ad6745f7a4f37ead58a45ebb.md)

[[demo-mode.md](http://demo-mode.md)](perfect-corp/demo-mode%20md%2011d75fb30c1340a1b99b1ca1a4c7767f.md)

[[request-response-contract.md](http://request-response-contract.md)](perfect-corp/request-response-contract%20md%20aae7b3a57be047519c66ab929f54f5d5.md)

[[fallback-behavior.md](http://fallback-behavior.md)](perfect-corp/fallback-behavior%20md%20aef70c05e4544f689beb494f84452d69.md)