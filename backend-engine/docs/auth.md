# Auth

## Current model

- Laravel Sanctum tokens protect API access
- Admin, vendor, and customer permissions must be enforced server-side
- Frontend hiding is not a security boundary

## Roles

- Customer: storefront account actions and try-on
- Vendor: own shop and products only
- Admin: platform-level management and moderation

## Rules

- Vendor users must not access admin-only data
- Customers must not access vendor or admin routes
- Token responses must not include secrets

