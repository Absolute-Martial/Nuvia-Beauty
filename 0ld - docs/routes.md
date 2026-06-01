# Route Inventory

This file summarizes the main surfaces by app. It is intentionally not exhaustive, but it should stay close to the actual exported page tree.

Detailed route inventories now live in the app docs:

- [Backend Engine routes](../backend-engine/docs/routes.md)
- [Admin Panel routes](../admin-panel/docs/routes.md)
- [Vendor Portal routes](../vendor-portal/docs/routes.md)
- [Storefront routes](../storefront/docs/routes.md)

## Admin Panel

Main areas:

- Auth: `login`, `register`, `forgot-password`, `verify-email`, `logout`
- Dashboard and platform overview
- Catalog: products, categories, brands, tags, attributes, groups, coupons, FAQs, flash sales
- Commerce: orders, refunds, shipping, taxes, withdrawals, transactions
- Shop management: shops, staff, messages, store notices, settings
- User management: admins, vendors, customers, vendor staff, my staff
- Policy and support: refund policies, terms and conditions, notice boards, review queues

## Vendor Portal

Main areas:

- Auth and profile: `login`, `register`, `forgot-password`, `verify-email`, `profile-update`, `logout`
- Shop dashboard and owner views
- Products and inventory
- Orders and transactions
- Withdrawals
- Shop notices and messages
- Vendor staff management
- Shop settings and maintenance screens

## Storefront

Main areas:

- Homepage and discovery: `/`, products, offers, FAQ, privacy, terms, search, collections, category pages
- Auth and customer account: signin, signup, OTP login, verify-email, forget-password, logout
- Checkout and payment: checkout, guest checkout, order payment, thank-you routes
- My account: orders, downloads, wishlists, cards, address, contact number, password change
- Shop pages: shop home, offers, FAQ, terms, contact-us
- Seller entry: become-seller

## Backend

The backend route surface is registered from `backend-engine/packages/marvel/src/Rest/Routes.php` and the Laravel route files in `backend-engine/routes/`. See the backend app docs for the detailed inventory.
