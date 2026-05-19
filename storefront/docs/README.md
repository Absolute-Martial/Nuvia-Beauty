# 🛍️ Storefront Application Documentation

The storefront is the customer-facing e-commerce shopping experience.

---

## 🛠️ Tech Stack

* **Framework**: Next.js `15.5.18` (Pages Router)
* **Library**: React `19.2.6`
* **Default Port**: `3003`

---

## 💻 Developer Commands

| Action | Command | Description |
| --- | --- | --- |
| **Run Dev Server** | `yarn workspace @nuvia/storefront dev` | Starts local Next.js dev server on port `3003`. |
| **Build Project** | `yarn workspace @nuvia/storefront build` | Compiles production storefront build bundle. |
| **Start Production** | `yarn workspace @nuvia/storefront start` | Boots production server. |
| **Run Lint** | `yarn workspace @nuvia/storefront lint` | Validates styling and code syntax. |

---

## 🔑 Environment Variables

The following parameters are required in `storefront/.env`:

| Parameter | Purpose / Notes |
| --- | --- |
| `NEXT_PUBLIC_REST_API_ENDPOINT` | URL of the Backend Engine API (e.g. `http://localhost:8000/api`). |
| `NEXT_PUBLIC_SITE_URL` | Canonical URL of the storefront client. |
| `NEXT_PUBLIC_DEFAULT_LANGUAGE` | Default locale code (e.g., `en`). |
| `NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY` | Public credential key for processing checkout card elements. |
| `NEXTAUTH_URL` | Session authentication redirect endpoint. |
| `SECRET` | Token encoding secret key. |

---

## 🗺️ Client Routing Layout

* **Discovery**: Home feed, catalog listings, categorization, flash-deals.
* **Authentication**: Login, email validation, registration, password recovery.
* **Fulfillment**: Cart management, shipping form inputs, Stripe payment portal, order receipts.
* **Account**: Historical orders, profile details, addresses, and wishlist manager.
* **Vendor**: Seller registration landing.

---

## 📂 Key Architecture Paths

* 🛠️ `src/pages/` — Pages-router client views.
* 🏠 `src/components/pages/refined-home.tsx` — Custom home layout.
* 👥 `src/components/become-seller/` — Vendor sign-up form components.
* 📦 `src/pages/api/auth/[...nextauth].ts` — Auth routing configuration.

> [!NOTE]
> Storefront product media rendering is dependent on the backend media disk config being validly defined and accessible.
