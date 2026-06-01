# Phase 0.1 — Environment & Service Baseline

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-0/phase-0.1-environment-service-baseline.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) › **Phase 0.1**

## Overview

Establishes a known-good local and shared environment baseline before any feature work. This sub-phase confirms that every runtime service in the Nuvia Beauty stack starts, is reachable on its expected port, and is wired to consistent environment configuration. Without a verified baseline, later phases cannot distinguish new defects from pre-existing environment drift.

## Objectives

- Confirm all core services boot and are reachable.
- Standardize environment variables across services.
- Produce a reproducible “green baseline” that later phases can trust.

## Scope

**In scope**

- Backend API (Laravel ^13 / PHP ^8.3, port 8000).
- Storefront (Next.js 15.5.18 / React 19.2.6, port 3003), admin-panel (3002), vendor-portal (3004).
- MySQL 8 (3306), Redis 7.4 (6379), S3-compatible object store (MinIO/AIStor, 9000/9001).
- `.env` consistency across services.

**Out of scope**

- Functional/business logic verification (later sub-phases).
- Production infrastructure provisioning (deployment docs).

## Business Context

A reliable baseline reduces wasted engineering time chasing environment-specific failures and is a prerequisite for the demo and push-readiness goals in [Phase 6 — Demo & Push Readiness](../Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md). It directly supports predictable onboarding for engineering and operations teams.

## Functional Requirements

- Each service responds on its documented port.
- Health endpoints (or equivalent root responses) return success.
- Cross-service configuration (API base URLs, bucket names) is consistent.

## Technical Requirements

- Documented service matrix with ports and start commands.
- Single source of truth for shared env values (API base URL, S3 endpoint, bucket names, Redis/MySQL DSNs).
- Versions pinned: PHP ^8.3, Laravel ^13, Node/Next 15.5.18, React 19.2.6, MySQL 8, Redis 7.4.

## Architecture Impact

- No schema or API changes. Establishes the operational substrate the domain layers (Storage, Beauty) depend on. Confirms the service topology described in the HLD.

## Dependencies

- Repository access (validated in [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md)).
- None upstream; this is the first executable baseline check.

## Detailed Implementation Tasks

- [ ]  Document the service/port matrix (API 8000, storefront 3003, admin 3002, vendor 3004, MySQL 3306, Redis 6379, S3 9000/9001).
- [ ]  Boot each service locally and capture start output.
- [ ]  Verify MySQL and Redis connectivity from the API container/host.
- [ ]  Verify the S3 endpoint is reachable and credentials resolve.
- [ ]  Reconcile `.env` / `.env.example` across all four apps.
- [ ]  Record the baseline in the evidence folder.

## Deliverables

- Service/port matrix document.
- Verified `.env.example` per app.
- Baseline evidence entry (start logs, connectivity checks).

## Testing & Validation Strategy

- Manual smoke: hit each service root/health.
- Connectivity tests: MySQL ping, Redis ping, S3 list-buckets.
- Config diff: ensure no drift between `.env` and `.env.example`.

## Acceptance Criteria

- All services start without fatal errors.
- MySQL, Redis, and S3 are reachable from the API.
- Env files are consistent and documented.

## Exit Criteria

- A reproducible green baseline is recorded and can be re-run by any engineer.

## Risks & Mitigations

- **Port conflicts** → document and standardize ports; provide override guidance.
- **Env drift** → enforce `.env.example` as the canonical template.
- **S3 endpoint mismatch (path vs virtual-host)** → document MinIO/AIStor endpoint style explicitly.

## Rollout Plan

- Local first, then shared/staging. No production impact. Communicate the baseline matrix to all teams.

## Success Metrics

- 100% of core services reachable on first boot using documented steps.
- Zero env-drift findings on re-run.

## Related Documentation

- Parent: [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Architecture: [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21) · Deployment: [[production-readiness.md](http://production-readiness.md)](../../../deployment/production-readiness%20md%204a0fae0daf6e4e87ab5a75757909ec84.md)

## Future Considerations

- Automate the baseline as a single `make doctor` / health script.
- Add containerized one-command bring-up for new engineers.