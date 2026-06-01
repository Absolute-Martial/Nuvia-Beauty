# settings-management.md

Owner: Susank Shakya

<aside>
🎛️

**`docs/admin-panel/settings-management.md`** · the admin UI over the backend settings system.

</aside>

# 1. Purpose & scope

This page describes the **admin UI over the backend settings system** (`backend-engine/settings-system.md`). It lets operators change safe runtime behavior without a redeploy, while secrets and endpoints remain in environment only.

# 2. Editable here

| Setting | Purpose |
| --- | --- |
| Feature flags | Enable/disable capabilities per phase. |
| Demo-mode toggle | Force deterministic demo-mode for providers. |
| Thresholds | e.g. minimum confidence to surface a recommendation. |
| TTL overrides | Signed-URL TTLs within safe bounds. |

# 3. Not editable here

- Secrets and endpoints (storage, providers, DB) — these live in **environment only** and never appear in the editable store.

# 4. Behavior

- Values are typed/validated on save (Enums/DTOs); invalid values are rejected.
- Changes take effect at **runtime** and are **audit-logged**.
- Resolution falls back safely: settings store → environment → code default.

# 5. Related documentation

- Backend: `backend-engine/settings-system.md`. Demo-mode: `integrations/perfect-corp/demo-mode.md`. Audit: `audit-views.md`.