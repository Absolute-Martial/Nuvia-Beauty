# API Dependencies

The storefront depends on the backend REST API for catalog, shop, order, and
try-on data.

## Called from the browser

- product listing
- category data
- shop pages
- customer auth
- cart and checkout shell
- try-on upload and polling

## Rules

- Use only browser-safe env values in client code
- Do not put private service credentials in the frontend bundle
- Keep payment calls disabled while the MVP has payments hidden
- Vendor operations belong in the separate vendor app, not the storefront
