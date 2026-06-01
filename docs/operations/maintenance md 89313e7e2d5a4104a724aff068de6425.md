# maintenance.md

Owner: Susank Shakya

<aside>
🔧

**`docs/operations/maintenance.md`** · routine upkeep that keeps the platform healthy and costs controlled.

</aside>

# 1. Purpose & scope

This page defines **recurring maintenance** — the scheduled work that keeps the platform secure, performant, and cost-controlled. Maintenance is planned, announced when user-facing, and evidenced for risky changes.

# 2. Recurring tasks

| Task | Cadence | Notes |
| --- | --- | --- |
| Media retention | Continuous / daily | Expire/delete media past its window; honor consent withdrawals. |
| Dependencies | Regular cadence | Patch Laravel/Next.js and base images; review CVEs. |
| Database | Weekly | Monitor growth, indexes, and backup integrity. |
| Queues | Weekly | Clear dead-letters, review retry patterns. |
| Secrets | Periodic + on suspicion | Rotate provider/storage credentials. |

# 3. Maintenance windows

- Schedule windows during low-traffic periods; announce if user-facing.
- Prefer rolling, zero-downtime changes; have a rollback path ready ([[rollback.md](http://rollback.md)](../deployment/rollback%20md%20ce2b95b2b57b431891c2528be2613c84.md)).

# 4. Evidence

- Capture before/after evidence for risky changes; link from the relevant phase under `implementation-plan/evidence/`.

# 5. Related documentation

- Monitoring: [[monitoring.md](http://monitoring.md)](monitoring%20md%2025dd9e9c06a94ff7998ee1271ff6a335.md). Backups: [[backup-restore.md](http://backup-restore.md)](backup-restore%20md%202ddc8e163c0545908bed05ddb3fa5899.md). Lifecycle: `storage/media-lifecycle.md`. Config: `deployment/environment-variables.md`.