# 🏪 Vendor Portal Application Documentation

The vendor portal is the dedicated control dashboard for shop owners to manage listings, orders, payouts, and store preferences.

---

## 🛠️ Tech Stack

* **Framework**: Next.js `15.5.18` (Pages Router)
* **Library**: React `19.2.6`
* **Default Port**: `3004`

---

## 💻 Developer Commands

| Action | Command | Description |
| --- | --- | --- |
| **Run Dev Server** | `yarn workspace @nuvia/vendor-portal dev` | Starts local Next.js dev server on port `3004`. |
| **Build Project** | `yarn workspace @nuvia/vendor-portal build` | Compiles production vendor-portal bundle. |
| **Start Production** | `yarn workspace @nuvia/vendor-portal start` | Boots production server. |
| **Run Lint** | `yarn workspace @nuvia/vendor-portal lint` | Validates styling and code syntax. |

---

## 🔑 Environment Variables

Core variables are defined in `vendor-portal/.env`:

| Parameter | Purpose / Notes |
| --- | --- |
| `NEXT_PUBLIC_REST_API_ENDPOINT` | URL of the Backend Engine API (e.g. `http://localhost:8000/api`). |
| `NEXT_PUBLIC_SHOP_URL` | Canonical URL of the customer-facing storefront. |
| `NEXT_PUBLIC_AUTH_TOKEN_KEY` | Storage key used for vendor auth tokens. |
| `NEXT_PUBLIC_DEFAULT_LANGUAGE` | Default locale code (e.g. `en`). |
| `NEXT_PUBLIC_API_BROADCAST_DRIVER` | Broadcaster service driver (e.g. `pusher`). |
| `NEXT_PUBLIC_PUSHER_APP_KEY` | Realtime messaging application keys. |

---

## 🗺️ Vendor Route Layout

* **Overview**: Sales metrics, shop performance charts.
* **Catalog Management**: Vendor product catalog, stock counts, draft products, brand attributes.
* **Fulfillment Management**: Shop orders, status changes, delivery scheduling.
* **Payout Controls**: Wallet checks, withdrawal requests, payment logs.
* **Support**: Direct messaging customer queues, store notice banner settings.
* **Settings**: Shop details, opening hours, contact details, maintenance toggles.

---

## 📂 Key Architecture Paths

* 🛠️ `src/pages/` — Pages-router vendor views.
* 🏪 `src/components/layouts/shop/` — Master vendor container layout.
* 📦 `src/components/products/` — Vendor catalog upload interfaces.
* 💬 `src/components/message/` — Live messenger features.

> [!WARNING]
> Keep admin-only features out of this application. If a workflow depends on backend permissions, document those specific Laravel permissions here.
