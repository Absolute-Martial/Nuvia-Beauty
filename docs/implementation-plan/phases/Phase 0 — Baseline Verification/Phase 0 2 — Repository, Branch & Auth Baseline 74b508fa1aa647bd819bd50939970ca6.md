# Phase 0.2 — Repository, Branch & Auth Baseline

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-0/phase-0.2-repo-branch-auth-baseline.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) › **Phase 0.2**

## Overview

Verifies that the repository, working branch, and Git authentication are correct and consistent so that committed work lands on the intended remote and branch. This addresses a known issue where pushes were authenticated as the wrong account, which risks lost or misrouted work.

## Objectives

- Confirm the canonical remote and the `development` working branch.
- Verify Git identity and credentials push to the correct account/remote.
- Document a safe local-work and commit procedure.

## Scope

**In scope**

- Remote URL verification (`Absolute-point/Nuvia-Beauty`).
- Branch model (`development` as the integration branch).
- Git auth (`gh auth`/credential helper) sanity.

**Out of scope**

- The full auth/push remediation (handled in [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)).

## Business Context

Misrouted commits cost time and create confusion across teams. A correct repo/branch/auth baseline protects engineering throughput and the integrity of the shared history.

## Functional Requirements

- `git remote -v` shows the canonical origin.
- The active branch is `development` (or a feature branch off it).
- A test push reaches the correct remote under the correct identity.

## Technical Requirements

- Canonical remote: `https://github.com/Absolute-Point/Nuvia-Beauty.git` (confirm exact org casing during execution).
- Credential helper or `gh` login bound to the correct account.
- `.gitignore` excludes secrets and build artifacts.

## Architecture Impact

- None to runtime architecture; governs source-control hygiene and CI inputs.

## Dependencies

- [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) environment baseline.

## Detailed Implementation Tasks

- [ ]  Run `git remote -v` and confirm origin.
- [ ]  Confirm current branch is `development`.
- [ ]  Verify `git config user.name`/`user.email` and `gh auth status`.
- [ ]  Perform a no-op/test commit + push to confirm identity and routing.
- [ ]  Document the local-work + commit procedure for the team.

## Deliverables

- Verified remote/branch configuration note.
- Documented commit/push procedure.

## Testing & Validation Strategy

- Dry-run push to a scratch branch; confirm it appears under the correct account.
- Confirm protected-branch expectations for `development`.

## Acceptance Criteria

- Origin, branch, and identity are confirmed correct.
- A test push lands on the intended remote/branch.

## Exit Criteria

- Engineers can commit and push with confidence to `development`.

## Risks & Mitigations

- **Wrong-account auth** → re-run `gh auth login` / `gh auth setup-git`; full fix tracked in [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md).
- **Accidental secret commit** → enforce `.gitignore` and pre-commit checks.

## Rollout Plan

- Apply per engineer workstation; communicate the standard procedure.

## Success Metrics

- 0 misrouted pushes after baseline.
- 100% of engineers on the documented procedure.

## Related Documentation

- Parent: [Phase 0 — Baseline Verification](../Phase%200%20%E2%80%94%20Baseline%20Verification%201686fde5c5734d7f80afd5b38b137a97.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Stabilization: [Phase 3 — Stabilization & Validation](../Phase%203%20%E2%80%94%20Stabilization%20&%20Validation%20092914da0d7549d6b897c87e23f3c03b.md)

## Future Considerations

- Add CI checks that reject commits with secrets or wrong author domains.
- Adopt signed commits.