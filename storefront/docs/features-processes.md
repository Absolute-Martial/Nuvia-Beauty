# Storefront Features and Processes

Documentation for the current `storefront` service.

## Current role

`storefront` is the customer-facing shopping frontend. It communicates with `backend-engine` through the configured REST API endpoint.

## Current feature areas

```text
customer storefront UI
product browsing UI
shopping interface components
customer session-aware views
PWA-capable frontend
localized interface support
backend API consumption
beauty recommendation request and display UI
```

## Current process flow

```text
Customer opens storefront
  -> storefront loads customer UI
  -> storefront requests data from backend
  -> backend returns data
  -> storefront renders customer-facing views
```

Phase 4 product recommendation flow:

```text
Customer opens a product detail page
  -> storefront renders the beauty recommendation panel
  -> customer submits skin/tone/concern tags
  -> storefront calls backend REST endpoint /api/v1/beauty/recommendations/generate
  -> backend returns score, confidence, reasons, warnings, and product metadata
  -> storefront renders recommendation cards
```

## Current UI tooling

| Area | Packages |
|---|---|
| HTTP | Axios |
| Server state | React Query |
| Local state | Jotai |
| User sessions | next-auth |
| PWA | next-pwa |
| Forms | React Hook Form, Yup |
| i18n | next-i18next, i18next, react-i18next |
| Styling | Tailwind CSS |

## PWA process

`next-pwa` is active outside development mode.

```text
NODE_ENV=development -> PWA disabled
other builds -> PWA plugin active
```

## Current Phase 4 implementation note

The recommendation UI is intentionally frontend-only in presentation and backend-only in business logic:

- browser requests use existing REST plumbing
- no database or provider access exists in the storefront
- no storage credentials are exposed to the browser bundle

## Maintenance rule

Update this file when customer-facing features, shopping processes, PWA behavior, or frontend process flow changes.
