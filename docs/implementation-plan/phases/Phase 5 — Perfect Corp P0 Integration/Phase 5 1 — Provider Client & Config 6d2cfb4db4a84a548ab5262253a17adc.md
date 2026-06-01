# Phase 5.1 — Provider Client & Config

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-5/phase-5.1-provider-client-config.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) › **Phase 5.1**

## Overview

Introduces the Perfect Corp / YouCam provider client and its configuration surface, establishing a safe, disabled-by-default integration boundary for the P0 AI Skin Analysis capability.

## Objectives

- Build a typed provider client (`PerfectCorpClient`).
- Define all provider config/env keys with safe defaults.
- Keep the integration disabled and in demo mode by default.

## Scope

**In scope**

- `PerfectCorpClient` (auth, base URL, timeouts, polling config).
- Env: `PERFECT_CORP_API_BASE_URL`, `PERFECT_CORP_API_KEY`, `PERFECT_CORP_API_BEARER_KEY`, `PERFECT_CORP_ENABLED=false`, `PERFECT_CORP_DEMO_MODE=true`, `PERFECT_CORP_TIMEOUT_SECONDS=60`, `PERFECT_CORP_POLL_INTERVAL_SECONDS=2`, `PERFECT_CORP_MAX_ATTEMPTS=60`.

**Out of scope**

- Orchestration jobs ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Business Context

The provider integration enables objective skin analysis to strengthen recommendations; a disabled-by-default client lets us build and demo safely before going live.

## Functional Requirements

- Client can authenticate and call the provider when enabled.
- Config is fully externalized via env with safe defaults.

## Technical Requirements

- HTTP client with auth headers, timeouts, retry/poll config.
- Config file mapping env keys; `ENABLED=false`/`DEMO_MODE=true` defaults.

## Architecture Impact

- Adds an external-provider boundary to the Beauty domain, isolated behind a client + config.

## Dependencies

- [Phase 4 — Seller Consultation Foundation](../Phase%204%20%E2%80%94%20Seller%20Consultation%20Foundation%209a2b4d302d2e483cba1b4c6f08b02fc3.md) (sessions/tasks model).

## Detailed Implementation Tasks

- [ ]  Implement `PerfectCorpClient` with auth + timeouts.
- [ ]  Add provider config + env keys with defaults.
- [ ]  Add config validation + connectivity self-check (no-op when disabled).
- [ ]  Unit tests with mocked HTTP.

## Deliverables

- `PerfectCorpClient` + provider config + tests.

## Testing & Validation Strategy

- Unit tests with mocked provider responses; config default assertions.

## Acceptance Criteria

- Client + config exist; disabled/demo defaults verified.

## Exit Criteria

- Provider boundary ready for orchestration jobs.

## Risks & Mitigations

- **Secret leakage** → env-only secrets; never log keys.
- **Accidental live calls** → disabled by default + explicit gate ([Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md)).

## Rollout Plan

- Disabled in all environments until the live-mode gate.

## Success Metrics

- 0 live calls while disabled; config validated.

## Related Documentation

- Parent: [Phase 5 — Perfect Corp P0 Integration](../Phase%205%20%E2%80%94%20Perfect%20Corp%20P0%20Integration%20cc62d7887457496680d61c1dda9cb917.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- PoC: [PoC 12 — Storage & Beauty Intelligence Foundation (Phase 4)](https://app.notion.com/p/PoC-12-Storage-Beauty-Intelligence-Foundation-Phase-4-0b6ab14688844db88c69148cff827930?pvs=21) · Architecture: [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21)

## Future Considerations

- Multi-provider abstraction; additional analysis APIs.