# API

## API groups

- Public marketplace: settings, categories, products, shops
- Auth: token login, user session, permissions
- Admin: platform product, vendor, user, and moderation operations
- Vendor: owned shop and product operations
- Try-on: upload source photo, create task, poll task
- Cart and order shell: retained where the legacy UI requires it

## Route sources

- Root route files live under Laravel `routes/`
- Main Marvel REST routes live under `packages/marvel/src/Rest/Routes.php`

## Rules

- Do not expose payment completion routes for MVP
- Keep destructive/payment endpoints guarded even if legacy routes remain
- Keep vendor endpoints ownership-scoped so the `vendor` app can replace
  admin-hosted vendor flows cleanly
- Add new routes to this file when behavior changes
