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
```

## Current process flow

```text
Customer opens storefront
  -> storefront loads customer UI
  -> storefront requests data from backend
  -> backend returns data
  -> storefront renders customer-facing views
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

## Planned feature notes

Mark unfinished customer and beauty features as planned until components and backend routes exist.

## Maintenance rule

Update this file when customer-facing features, shopping processes, PWA behavior, or frontend process flow changes.
