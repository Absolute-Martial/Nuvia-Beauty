# audit-views.md

Owner: Susank Shakya

<aside>
🔍

**`docs/admin-panel/audit-views.md`** · operator-facing, read-only views over the audit log.

</aside>

# 1. Purpose & scope

This page describes the **operator-facing views over the audit log** (`storage/audit-logging.md`). The views are strictly read-only and exist to support oversight and incident investigation — they never expose media or full signed URLs.

# 2. Views

| View | Shows |
| --- | --- |
| Storage access | Signed-URL mints, uploads, deletions (who / what / when). |
| Sensitive actions | Settings changes, catalog edits, consent events. |
| Filters | By actor, object, action, and time range. |

# 3. Guarantees

- **Read-only** — the panel cannot alter audit records.
- No media or full signed URLs are ever displayed (only references/identifiers).
- Supports incident investigation per the operations runbooks.

# 4. Typical use

- Investigate “who accessed this object and when” during an incident.
- Confirm a settings or consent change and trace it to an actor.
- Filter sensitive actions by time range for periodic review.

# 5. Related documentation

- Source: `storage/audit-logging.md`. Runbooks: `operations/`. Backend: `backend-engine/database-schema.md` (`audit_logs`).