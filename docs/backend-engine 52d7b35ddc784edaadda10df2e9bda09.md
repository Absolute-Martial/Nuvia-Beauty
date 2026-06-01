# backend-engine/

Owner: Susank Shakya

<aside>
📁

**`docs/backend-engine/`** — the Laravel backend: API contracts, database schema, queue jobs, settings, validation, and agent guardrails.

</aside>

`backend-engine` (PHP ^8.3 / Laravel ^13.0, `:8000`) is the single trusted tier — it owns every API, all data, and all external-provider orchestration. The three frontends are thin clients that never hold credentials. This folder documents the backend's surface and the rules that keep it safe ([ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0013, 0014, 0015).

# Contents

| Document | Covers |
| --- | --- |
| [[README.md](http://README.md)](backend-engine/README%20md%20a1b15f3b3f8b4e878a578e334296264d.md) | Backend overview, tech stack, domain modules, and layering. |
| [[api-contracts.md](http://api-contracts.md)](backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md) | API surface, payloads, errors, and conventions. |
| [[database-schema.md](http://database-schema.md)](backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) | Core entities, relationships, tenancy, and retention. |
| [[queue-jobs.md](http://queue-jobs.md)](backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md) | Background jobs, queue topology, and reliability. |
| [[settings-system.md](http://settings-system.md)](backend-engine/settings-system%20md%20065f2b0084fa4b06a7265a35d0c65467.md) | Typed, layered runtime configuration. |
| [[validation.md](http://validation.md)](backend-engine/validation%20md%20a11c1a59a2f44f339fdaee8e884154d4.md) | Type-safety and request validation strategy. |
| [](backend-engine/Untitled%20fa51edbb55544e499332c91e75809fb1.md) | Hard rules for building the backend (REST-first, redaction, idempotency, `AGENTS.md`). |

# Principles (ADR 0013)

- Laravel is the primary backend; Rust may be added later only for isolated, internal, compute-heavy services.
- Rust-like safety in Laravel: Larastan/PHPStan (max), `declare(strict_types=1)`, DTOs + Enums at boundaries, strict Form Requests + Policies.

[[README.md](http://README.md)](backend-engine/README%20md%20a1b15f3b3f8b4e878a578e334296264d.md)

[[api-contracts.md](http://api-contracts.md)](backend-engine/api-contracts%20md%209468ad6610754cc38b2fff1900887999.md)

[[database-schema.md](http://database-schema.md)](backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md)

[[settings-system.md](http://settings-system.md)](backend-engine/settings-system%20md%20065f2b0084fa4b06a7265a35d0c65467.md)

[[queue-jobs.md](http://queue-jobs.md)](backend-engine/queue-jobs%20md%209ec82aa303ec4d25818ec725c4f0d3d7.md)

[[validation.md](http://validation.md)](backend-engine/validation%20md%20a11c1a59a2f44f339fdaee8e884154d4.md)

[](backend-engine/Untitled%20fa51edbb55544e499332c91e75809fb1.md)