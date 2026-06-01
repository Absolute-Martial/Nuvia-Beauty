# backup-restore.md

Owner: Susank Shakya

<aside>
💾

**`docs/operations/backup-restore.md`** · protecting data and proving we can recover it.

</aside>

# 1. Purpose & scope

This page defines **what we back up, how we restore, and our recovery targets**. Recovery is rehearsed before production go-live so a real restore is routine, not improvised.

# 2. Backups

| Asset | Approach |
| --- | --- |
| Database | Regular MySQL backups with retention; integrity-checked. |
| Storage | Private buckets backed up/versioned per provider capability. |
| Config | Environment templates kept current (`deployment/environment-variables.md`). |

# 3. Restore procedure

1. Identify the last good backup.
2. Restore to a staging instance and verify (data integrity + core journeys).
3. Promote once verified.
- Follow [[database-restore.md](http://database-restore.md)](runbooks/database-restore%20md%208f61552b61594964aaf3c508e2fec1ef.md) for the detailed steps. Restores are rehearsed before production go-live (`implementation-plan/checklists/`).

# 4. Recovery targets

- **RPO** — bounded by backup frequency (minimize data loss window).
- **RTO** — target a quick restore-to-staging-then-promote; rehearsed timing recorded as evidence.

# 5. Evidence

- Store restore-test results under `implementation-plan/evidence/staging-verification/`.

# 6. Related documentation

- Restore runbook: [[database-restore.md](http://database-restore.md)](runbooks/database-restore%20md%208f61552b61594964aaf3c508e2fec1ef.md). Readiness: `deployment/production-readiness.md`. Storage: `storage/storage-architecture.md`.