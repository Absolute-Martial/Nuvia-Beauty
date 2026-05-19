# 🛡️ Admin Panel Application Documentation

The admin panel is the platform control dashboard for catalog, shops, orders, users, settings, and support workflows.

---

## 🛠️ Tech Stack

* **Framework**: Next.js `15.5.18` (Pages Router)
* **Library**: React `19.2.6`
* **Default Port**: `3002`

---

## 💻 Developer Commands

| Action | Command | Description |
| --- | --- | --- |
| **Run Dev Server** | `yarn workspace @nuvia/admin-panel dev` | Starts local Next.js dev server on port `3002`. |
| **Build Project** | `yarn workspace @nuvia/admin-panel build` | Compiles production admin-panel bundle. |
| **Start Production** | `yarn workspace @nuvia/admin-panel start` | Boots production server. |
| **Run Lint** | `yarn workspace @nuvia/admin-panel lint` | Validates styling and code syntax. |

---

## 🔑 Environment Variables

Core variables are defined in `admin-panel/.env`:

| Parameter | Purpose / Notes |
| --- | --- |
| `NEXT_PUBLIC_REST_API_ENDPOINT` | URL of the Backend Engine API (e.g. `http://localhost:8000/api`). |
| `NEXT_PUBLIC_SHOP_URL` | Canonical URL of the customer-facing storefront. |
| `NEXT_PUBLIC_AUTH_TOKEN_KEY` | Storage key used for administrative auth tokens. |
| `NEXT_PUBLIC_DEFAULT_LANGUAGE` | Default locale code (e.g. `en`). |
| `NEXT_PUBLIC_API_BROADCAST_DRIVER` | Broadcaster service driver (e.g. `pusher`). |
| `NEXT_PUBLIC_PUSHER_APP_KEY` | Realtime messaging application keys. |

---

## 🗺️ Administrative Route Layout

* **Dashboard**: Key metric summaries, order statistics, registrations.
* **Catalog Management**: Products, categories, brands, groups, tags, flash sales, coupons.
* **Fulfillment Management**: System order states, refunds, shipping rules, tax classes, withdrawal payouts.
* **User Controls**: Vendor application approvals, user account states, administrative staff assignments.
* **Config**: Globals, banners, support queries, policy documents, notifications.

---

## 📂 Key Architecture Paths

* 🛠️ `src/pages/` — Pages-router admin views.
* 🛡️ `src/components/layouts/admin/` — Master admin container layout.
* 📦 `src/components/products/` — Catalog edit forms.
* 🗄️ `src/components/settings/` — Global admin settings forms.

> [!IMPORTANT]
> Keep the documentation folders aligned with actual route structures. If files are relocated, update the corresponding documentation under the repo-wide `/docs/` root as well.
