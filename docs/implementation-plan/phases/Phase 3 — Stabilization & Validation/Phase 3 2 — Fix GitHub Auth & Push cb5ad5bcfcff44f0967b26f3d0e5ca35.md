# Phase 3.2 — Fix GitHub Auth & Push

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-3/phase-3.2-fix-github-auth-push.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md) › **Phase 3.2**

## Overview

Resolves the known blocker where pushes authenticated as the wrong account by fixing Git authentication and the remote, then pushing the `development` branch to the canonical repository.

## Objectives

- Authenticate Git as the correct account.
- Point the remote at the canonical repo.
- Push `development` successfully.

## Scope

**In scope**

- `gh auth login` / `gh auth setup-git`.
- `git remote set-url origin https://github.com/Absolute-point/Nuvia-Beauty.git`.
- Push `development` only.

**Out of scope**

- Console-boot fix ([Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)).

## Business Context

Until pushes land on the correct repo, no shared progress is possible; this unblocks collaboration and review.

## Functional Requirements

- Auth bound to the correct account.
- Remote URL correct.
- `development` pushed and visible on the canonical repo.

## Technical Requirements

- GitHub CLI auth + git credential helper.
- Correct origin URL (confirm exact org casing).
- Push restricted to `development`.

## Architecture Impact

- None to runtime; restores source-control flow.

## Dependencies

- [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md).

## Detailed Implementation Tasks

- [ ]  Run `gh auth login` and `gh auth setup-git` for the correct account.
- [ ]  `git remote set-url origin` to the canonical repo.
- [ ]  Verify identity via a scratch-branch push.
- [ ]  Push `development`.
- [ ]  Confirm commits appear under the correct account.

## Deliverables

- Correct auth + remote; pushed `development`.

## Testing & Validation Strategy

- Scratch-branch push verifies identity.
- Confirm remote history and author on the canonical repo.

## Acceptance Criteria

- `development` is pushed to the canonical repo under the correct identity.

## Exit Criteria

- Source-control flow restored; team can push/pull.

## Risks & Mitigations

- **Residual cached credentials** → clear and re-auth; verify with scratch push.
- **Pushing wrong branch** → restrict to `development`.

## Rollout Plan

- Per-engineer auth; one canonical push of `development`.

## Success Metrics

- 0 misrouted pushes; `development` visible on canonical repo.

## Related Documentation

- Parent: [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Baseline: [Phase 0.2 — Repository, Branch & Auth Baseline](../Phase%200%20%E2%80%94%20Baseline%20Verification/Phase%200%202%20%E2%80%94%20Repository,%20Branch%20&%20Auth%20Baseline%2074b508fa1aa647bd819bd50939970ca6.md)

## Future Considerations

- CI bot identity + branch protection rules.
- Signed commits.