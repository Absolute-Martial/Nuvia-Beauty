# database-restore.md

Owner: Susank Shakya

<aside>
🔄

**`docs/operations/runbooks/database-restore.md`** · recover from data loss or corruption with a verified restore.

</aside>

# 1. When to use

Data loss or corruption requiring a restore — a bad migration, accidental deletion, or integrity failure.

# 2. Severity

- **Sev1** — data integrity is at risk; act immediately and freeze writes where possible.

# 3. Steps

1. **Identify the last good backup** (integrity-checked) and the target recovery point.
2. Freeze writes / put the affected service in maintenance to prevent further drift.
3. **Restore to a staging instance** and verify data integrity + core journeys before touching production.
4. Promote the verified restore; reconcile any data written after the recovery point if required.
5. Capture evidence per the validation record.

# 4. Verification

- Restored data passes integrity checks; core journeys pass on the restored instance.
- No private media is exposed during or after the restore.

# 5. Escalation & communication

- Page the incident lead; communicate the recovery point and any unavoidable data-loss window.

# 6. Evidence & follow-up

- Record the timeline, recovery point, and verification; review in [[incident-response.md](http://incident-response.md)](../incident-response%20md%20ad458a5261d74cd490e700293324e098.md).

# 7. Related documentation

- Backups: [[backup-restore.md](http://backup-restore.md)](../backup-restore%20md%202ddc8e163c0545908bed05ddb3fa5899.md). Schema: `backend-engine/database-schema.md`. Readiness: `deployment/production-readiness.md`.