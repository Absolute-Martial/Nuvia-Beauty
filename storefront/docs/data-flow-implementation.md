# Storefront Data Flow and Implementation

Current data-flow documentation for `storefront`.

## Boundary

```text
storefront -> backend-engine -> protected services
```

The storefront is a browser-facing customer UI. It should use backend APIs for protected reads and mutations.

## Current request flow

```text
Customer UI action
  -> Axios / React Query call
  -> NEXT_PUBLIC_REST_API_ENDPOINT or configured rewrite
  -> backend-engine
  -> response rendered in storefront
```

## Current API rewrite

`storefront/next.config.js` includes a rewrite:

```text
/api-backend/:path* -> http://127.0.0.1:8000/:path*
```

This is current config behavior and should be reviewed when deployment routing changes.

## Current implementation tools

| Area | Current tools |
|---|---|
| HTTP | Axios |
| Server state | React Query |
| Local state | Jotai |
| Auth/session | next-auth |
| Forms | React Hook Form, Yup |
| PWA | next-pwa |
| Translation | next-i18next, i18next |

## Current image loading

Image domains are statically configured in `next.config.js` through `images.domains`.

Current note:

```text
The app does not yet use environment-driven image remotePatterns.
```

## Planned media flow

Not implemented yet.

Future private media access should be:

```text
storefront -> backend authorization endpoint -> short-lived media URL
```

Future uploads should use:

```text
storefront -> backend upload slot -> object storage upload -> backend confirm endpoint
```

## Update rule

Update this file when storefront API clients, request routing, image loading, PWA flow, or customer data-state patterns change.
