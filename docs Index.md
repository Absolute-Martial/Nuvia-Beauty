# docs/

<aside>
📁

**Documentation architecture for Nuvia Beauty** — the Notion mirror of the repo `docs/` folder. Each folder below is a Notion page; each file is a page inside it.

**Version:** 0.1.0 (held stable during early design).

</aside>

# Folder map

```
docs/
├── README.md
├── index.md
├── architecture/
│   ├── hld-system-architecture.md
│   ├── reference-architecture.md
│   ├── adr/            (README + ADR 0001–0013)
│   └── diagrams/       (context, container, sequence)
├── implementation-plan/
│   ├── README.md
│   ├── execution-order.md
│   ├── status.md
│   ├── risks.md
│   ├── decision-log.md
│   ├── phases/         (README + phase 0–9)
│   ├── sub-plans/      (8 plans)
│   ├── checklists/     (5 checklists)
│   └── evidence/       (README + test evidence)
├── product/          (concept, white paper, technical proposal, mvp-boundary, demo-scenarios)
├── rfc/              (README + RFC)
├── poc/              (README + PoC)
├── storage/          (architecture, media-lifecycle, private-bucket-policy, audit-logging)
├── integrations/     (perfect-corp/, ai-provider/)
├── backend-engine/   (api-contracts, database-schema, queue-jobs, settings-system, validation)
├── storefront/       (customer-flow, recommendation-ui, self-scan-flow, try-on-studio)
├── admin-panel/      (admin-flows, product-management, settings-management, audit-views)
├── vendor-portal/    (seller-consultation-flow, product-mapping, recommendations-review)
├── testing/          (backend, frontend, e2e, storage, security test plans)
├── deployment/       (local-setup, staging, production-readiness, env-vars, rollback)
└── operations/       (monitoring, maintenance, incident-response, backup-restore, runbooks/)
```

# Documentation conventions

## Status values

Use one of these for any area/phase/task status, in order of maturity:

- **Planned** — defined, not started.
- **In Progress** — actively being built.
- **Locally Implemented** — works locally; not yet on remote.
- **Remote Verified** — committed/pushed and confirmed on remote.
- **Tested – Staging Verified** — validated on staging with evidence.
- **Demo Ready** — stable and safe to demo.
- **Blocked** — cannot proceed; blocker noted.

## Per-phase document template

Every phase page uses these sections: **Goal · Scope · Out of Scope · Dependencies · Implementation Tasks · Acceptance Criteria · Validation Commands · Evidence · Risks · Rollback**.

## Validation evidence record

Each verified delivery test is recorded with: **Command executed · Environment · Result · Screenshot / log output · Failure notes · Fix applied · Retest result**.

## Dependency mapping

Later phases must declare the foundations they depend on (e.g. Phase 4 depends on verified Phase 1 + Phase 2 + storage validation + demo seed data). Do not start a phase before its dependencies are **Remote Verified** or better.

# Top-level folders

- [[README.md](http://README.md)](docs/README%20md%20b184623090a7497489d866861aaf066f.md) · [[index.md](http://index.md)](docs/index%20md%204211964b55384dbabbe1b2214effb74c.md)

[rfc/](docs/rfc%20b4c77b3049c54d55b929ddcff437d425.md)

[architecture/](docs/architecture%2081e20fb3c3a8425c9b43775212d8e80a.md)

- [architecture/](docs/architecture%2081e20fb3c3a8425c9b43775212d8e80a.md)
- [implementation-plan/](docs/implementation-plan%20dcdd3415ed5441189d05d8599585c8ac.md)
- [product/](docs/product%2085956a1e35d04bfe874ad81a53256f4b.md)
- [rfc/](docs/rfc%20b4c77b3049c54d55b929ddcff437d425.md)
- [poc/](docs/poc%203dda74d0e4ea4ebb81c917fb0a42ea83.md)
- [storage/](docs/storage%20598cb9d99ba3453d96f4b499ca8292cf.md)
- [integrations/](docs/integrations%20275725162e6d471b9a164ea22bdaea22.md)
- [backend-engine/](docs/backend-engine%2052d7b35ddc784edaadda10df2e9bda09.md)
- [storefront/](docs/storefront%2032d1330afe1a41e3a4bdcb6166c125f3.md)
- [admin-panel/](docs/admin-panel%204e99dd5333f1411fb6b40272824dabd8.md)
- [vendor-portal/](docs/vendor-portal%20a28cb0264c4a4963852d84920707414e.md)
- [testing/](docs/testing%20fb05a1e82065477d8aaead149521944f.md)
- [deployment/](docs/deployment%20373fed9ede5940a6afe17a24d267b11b.md)
- [operations/](docs/operations%200901779c4e0a4d97a8f2bade2b54758f.md)

[[README.md](http://README.md)](docs/README%20md%20b184623090a7497489d866861aaf066f.md)

[[index.md](http://index.md)](docs/index%20md%204211964b55384dbabbe1b2214effb74c.md)

[product/](docs/product%2085956a1e35d04bfe874ad81a53256f4b.md)

[storage/](docs/storage%20598cb9d99ba3453d96f4b499ca8292cf.md)

[integrations/](docs/integrations%20275725162e6d471b9a164ea22bdaea22.md)

[poc/](docs/poc%203dda74d0e4ea4ebb81c917fb0a42ea83.md)

[storefront/](docs/storefront%2032d1330afe1a41e3a4bdcb6166c125f3.md)

[backend-engine/](docs/backend-engine%2052d7b35ddc784edaadda10df2e9bda09.md)

[testing/](docs/testing%20fb05a1e82065477d8aaead149521944f.md)

[vendor-portal/](docs/vendor-portal%20a28cb0264c4a4963852d84920707414e.md)

[deployment/](docs/deployment%20373fed9ede5940a6afe17a24d267b11b.md)

[implementation-plan/](docs/implementation-plan%20dcdd3415ed5441189d05d8599585c8ac.md)

[operations/](docs/operations%200901779c4e0a4d97a8f2bade2b54758f.md)

[admin-panel/](docs/admin-panel%204e99dd5333f1411fb6b40272824dabd8.md)

[Feature Brainstorming — Nuvia Beauty](docs/Feature%20Brainstorming%20%E2%80%94%20Nuvia%20Beauty%205c82d2b2ff7d41bd863c2d7af1697826.md)

[Validation & Launch Readiness](docs/Validation%20&%20Launch%20Readiness%2040ad5e7abbcd4143b001ac13658d4ae6.md)

[](docs/Untitled%20112ab80459974651af504cd6045a86b2.md)