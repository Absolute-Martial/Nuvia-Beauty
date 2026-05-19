# Admin Panel Data Flow and Implementation

Current data-flow documentation for `admin-panel`.

## Boundary

```text
admin-panel -> backend-engine -> protected services
```

`admin-panel` is a browser-facing UI. It should use backend APIs for data reads and mutations.

## Current request flow

```text
Admin UI action
  -> Axios / React Query call
  -> NEXT_PUBLIC_REST_API_ENDPOINT
  -> backend-engine
  -> response rendered in UI
```

## Current implementation tools

| Area | Current tools |
|---|---|
| HTTP | Axios |
| Server state | React Query |
| Local state | Jotai |
| Forms | React Hook Form, Yup |
| Tables | rc-table, rc-pagination |
| Charts | ApexCharts |
| Translation | next-i18next, i18next |

## Current image loading

Image domains are statically configured in `next.config.js` through `images.domains`.

Current note:

```text
The app does not yet use environment-driven image remotePatterns.
```

## Planned storage/media flow

Not implemented yet.

Future private media access should be:

```text
admin-panel -> backend authorization endpoint -> short-lived media URL
```

## Update rule

Update this file when admin API clients, request patterns, image loading, or data-state patterns change.
