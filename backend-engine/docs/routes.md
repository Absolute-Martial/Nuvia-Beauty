# Backend Routes

The backend route surface is registered from `packages/marvel/src/Rest/Routes.php` and the Laravel route files in `routes/`.

## Public Routes

- Authentication: register, token login, logout, password reset, OTP login, social login
- Catalog lookup: products, categories, tags, authors, manufacturers, shops, settings, FAQs, flash sale, refund policy
- Utility endpoints: availability check, featured categories, nearby shops, payment intent, downloadable tokens
- Content and commerce flows: newsletter subscribe, order checkout verification, order payment submission
- Webhooks: Razorpay, Stripe, PayPal, Mollie, Paystack, Paymongo, Xendit, Iyzico, bKash, Flutterwave
- File operations: product import/export, attribute import/export, downloadable file helpers, invoice export

## Customer Routes

Protected by `auth:sanctum`, email verification, and customer permissions:

- Profile and account update
- Orders, reviews, questions, feedback, conversations, messages
- Wishlists and carts/attachments where applicable
- Try-on upload and task flows
- Addresses, cards, downloads, follow-shop flows

## Vendor Routes

Protected by shop-owner or staff permissions:

- Shop management
- Products, resources, attributes, attribute values, orders
- Store notices, FAQs, analytics, flash sale requests
- Withdrawals, staff management, ownership transfer

## Super Admin Routes

Protected by super-admin permissions:

- Types, categories, delivery times, languages, tags, refund reasons, resources
- Reviews, questions, feedback, abusive reports
- Settings, users, authors, manufacturers, taxes, shippings
- Shop approval, withdraw approval, coupon approval, flash-sale request approval
- Refund policies, notify logs, staff lists, new shops, seller onboarding

## Notes

- Keep this file aligned with `packages/marvel/src/Rest/Routes.php`.
- When a route moves between public, customer, vendor, or admin scope, update this inventory first.
