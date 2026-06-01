# Phase 7 — Personalized Domain Expansion

Owner: Susank Shakya

<aside>
7️⃣

**Phase 7 — Personalized Domain Expansion** · `docs/implementation-plan/phases/phase-7/` · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21) (repo `Absolute-point/Nuvia-Beauty` · `development`). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › **Phase 7 — Personalized Domain Expansion**

**Navigation:** Previous: [Phase 6 — Demo & Push Readiness](Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md) · Next: [Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)

This page is an overview and navigation hub. All detailed planning, tasks, deliverables, dependencies, and validation criteria live in the dedicated sub-phase pages listed below.

## Overview

Phase 7 expands Nuvia Beauty into a multi-tenant, personalized-domain platform: each shop gets its own branded subdomain with isolated tenancy, branded experiences, secure signed customer-profile links, and the DNS/TLS edge to serve it all — backed by strict privacy and audit controls.

## Scope

**In scope:** domain/subdomain strategy, tenant resolution middleware, per-shop settings/branding, signed profile access links, tenant-aware frontends, DNS/proxy/TLS, and privacy/audit.

**Out of scope:** customer self-scan ([Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)); try-on VTO ([Phase 9 — Try-On Studio & Makeup VTO](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)).

## Roadmap positioning

Phase 7 follows the demo milestone ([Phase 6 — Demo & Push Readiness](Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md)) and establishes the multi-tenant foundation that customer self-scan ([Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)) and try-on ([Phase 9 — Try-On Studio & Makeup VTO](Phase%209%20%E2%80%94%20Try-On%20Studio%20&%20Makeup%20VTO%20b81aa5d5e0a74c60808df37c6d7b1330.md)) build upon.

## Sub-phases

| Sub-phase | Focus |
| --- | --- |
| [Phase 7.1 — Domain & Subdomain Strategy](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%201%20%E2%80%94%20Domain%20&%20Subdomain%20Strategy%205a272fc832e045719f3ea1835ba05c34.md) | Domain scheme + tenant→domain mapping |
| [Phase 7.2 — Tenant Resolution Middleware](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%202%20%E2%80%94%20Tenant%20Resolution%20Middleware%206cce05e9e0774269a9cbcf383889b6db.md) | Host→tenant resolution + isolation |
| [Phase 7.3 — Shop Beauty Settings & Branding](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%203%20%E2%80%94%20Shop%20Beauty%20Settings%20&%20Branding%20790a6fbcd0e043a9ba484f5a7921244f.md) | Per-shop settings + branding |
| [Phase 7.4 — Signed Customer Profile Access Links](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%204%20%E2%80%94%20Signed%20Customer%20Profile%20Access%20Links%202e357537b9664e5581ac0cc2989feabe.md) | Signed, expiring profile access (hashes only) |
| [Phase 7.5 — Frontend (PWA, Vendor, Admin)](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%205%20%E2%80%94%20Frontend%20(PWA,%20Vendor,%20Admin)%20e8f3e836f9ff4040b3c8ebc86b57e22a.md) | Tenant-aware, branded frontends |
| [Phase 7.6 — DNS, Reverse Proxy & TLS](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%206%20%E2%80%94%20DNS,%20Reverse%20Proxy%20&%20TLS%20ea2a5cd9b32b44ef8d1b4ecba5f5b13e.md) | Wildcard DNS + proxy + automated TLS |
| [Phase 7.7 — Privacy & Audit](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%207%20%E2%80%94%20Privacy%20&%20Audit%20fc6aa096880347b88fde711fb4bc8bed.md) | Access policies + consent + audit logging |

## Phase-level exit criteria

- Shops resolve to isolated, branded tenants over valid HTTPS subdomains.
- Signed profile links work securely with hashes-only storage.
- Privacy and audit controls enforce isolation and log sensitive access.

## Related documentation

- Hub: [[index.md](http://index.md)](../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) · [phases/](../phases%2048c28ced7f964f33a064261f94b632ed.md) · [Phase Index — Canonical Naming & Template](Phase%20Index%20%E2%80%94%20Canonical%20Naming%20&%20Template%209ab2e9ed85a0417681d63789c64a98ad.md)
- Arch/Security: [High-Level Design (HLD) — System Architecture](https://app.notion.com/p/High-Level-Design-HLD-System-Architecture-f32e54776c7a4a91bb476be774d5d6a3?pvs=21) · [Request for Comments (RFC)](https://app.notion.com/p/Request-for-Comments-RFC-411c875deb6e4bb79489bf2e536e41cc?pvs=21) · Schema: [[database-schema.md](http://database-schema.md)](../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md) · Deployment: [[production-readiness.md](http://production-readiness.md)](../../deployment/production-readiness%20md%204a0fae0daf6e4e87ab5a75757909ec84.md)
- Prev: [Phase 6 — Demo & Push Readiness](Phase%206%20%E2%80%94%20Demo%20&%20Push%20Readiness%20dca2293497854e1195ad738f085079e1.md) · Next: [Phase 8 — Customer Self-Scan & Profile History](Phase%208%20%E2%80%94%20Customer%20Self-Scan%20&%20Profile%20History%20543c259c98be45ae8f0188cd310eef65.md)

## Sub-phase pages

[Phase 7.4 — Signed Customer Profile Access Links](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%204%20%E2%80%94%20Signed%20Customer%20Profile%20Access%20Links%202e357537b9664e5581ac0cc2989feabe.md)

[Phase 7.3 — Shop Beauty Settings & Branding](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%203%20%E2%80%94%20Shop%20Beauty%20Settings%20&%20Branding%20790a6fbcd0e043a9ba484f5a7921244f.md)

[Phase 7.5 — Frontend (PWA, Vendor, Admin)](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%205%20%E2%80%94%20Frontend%20(PWA,%20Vendor,%20Admin)%20e8f3e836f9ff4040b3c8ebc86b57e22a.md)

[Phase 7.7 — Privacy & Audit](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%207%20%E2%80%94%20Privacy%20&%20Audit%20fc6aa096880347b88fde711fb4bc8bed.md)

[Phase 7.2 — Tenant Resolution Middleware](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%202%20%E2%80%94%20Tenant%20Resolution%20Middleware%206cce05e9e0774269a9cbcf383889b6db.md)

[Phase 7.1 — Domain & Subdomain Strategy](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%201%20%E2%80%94%20Domain%20&%20Subdomain%20Strategy%205a272fc832e045719f3ea1835ba05c34.md)

[Phase 7.6 — DNS, Reverse Proxy & TLS](Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion/Phase%207%206%20%E2%80%94%20DNS,%20Reverse%20Proxy%20&%20TLS%20ea2a5cd9b32b44ef8d1b4ecba5f5b13e.md)