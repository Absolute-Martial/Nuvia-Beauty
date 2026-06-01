# Phase Index — Canonical Naming & Template

Owner: Susank Shakya

<aside>
🗂️

Use this page as the normalized phase index. Phase numbers use single-digit canonical file names (`phase-0`, `phase-1`, … `phase-9`), matching the Reference Docs repo (`docs/implementation-plan/phases/`). There are no fractional phases.

</aside>

## Canonical phase naming

| Canonical file name | Display name | Status | Primary focus |
| --- | --- | --- | --- |
| `phase-0-baseline-verification.md` | Phase 0 — Baseline Verification | Required before production | Environment, repo, and baseline validation |
| `phase-1-storage-recommendation-foundation.md` | Phase 1 — Storage & Recommendation Foundation | Implemented / validating | Storage, media metadata, mappings, deterministic recommendations |
| `phase-2-mapping-editors-events-signals.md` | Phase 2 — Mapping Editors & Event Signals | Implemented / validating | Admin/vendor mapping support and interaction signals |
| `phase-3-stabilization-validation.md` | Phase 3 — Stabilization & Validation | Current focus | QA, docs, API cleanup, staging validation |
| `phase-4-seller-consultation-foundation.md` | Phase 4 — Seller Consultation Foundation | Planned | Guided seller consultation workflow |
| `phase-5-perfect-corp-p0-integration.md` | Phase 5 — Perfect Corp P0 Integration | Planned | Backend-only provider orchestration |
| `phase-6-demo-push-readiness.md` | Phase 6 — Demo & Push Readiness | Planned | Demo, release, deployment, evidence |
| `phase-7-personalized-domain-expansion.md` | Phase 7 — Personalized Domain Expansion | Planned | Shop-specific domain, branding, and branded profile-reopen foundation |
| `phase-8-customer-self-scan-profile-history.md` | Phase 8 — Customer Self-Scan & Profile History | Planned | Self-scan, snapshots, reusable profile history |
| `phase-9-try-on-studio-makeup-vto.md` | Phase 9 — Try-On Studio & Makeup VTO | Later | Controlled virtual try-on |

## Standard phase page template

```markdown
# Phase X — Phase Name

## Objective

## Why this phase exists

## Scope

### Included

### Excluded

## User value

## Technical value

## Main features

## Backend work

## Admin-panel work

## Vendor-portal work

## Storefront work

## Data model changes

## API changes

## Security and privacy rules

## Acceptance criteria

## Test scenarios

## Demo scenario

## Risks

## Dependencies

## Exit criteria

## Related docs

## Related code paths
```

## Rules

- Use single-digit canonical phase numbers (`phase-0` … `phase-9`) to match the Reference Docs repo; do not introduce fractional phases (for example, no `phase-8.5`).
- Do not document unimplemented behavior as current.
- Every phase should include acceptance criteria, risks, and exit criteria.
- Keep Perfect Corp integration separate from the deterministic recommendation engine.
- Keep Try-On Studio after profile history and scan stability.
- Link code paths in each phase only when those paths exist or are intentionally planned.