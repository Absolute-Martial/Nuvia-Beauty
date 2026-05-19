# Vendor Portal Data Flow and Implementation

Current data-flow documentation for `vendor-portal`.

## Boundary

```text
vendor-portal -> backend-engine -> protected services
```

## Current request flow

```text
Vendor UI action
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
| Charts | ApexCharts, react-apexcharts |
| i18n | next-i18next, i18next, react-i18next |

## Planned storage/media flow

Not implemented yet.

Private or sensitive media should always be fetched via backend endpoints that issue short-lived signed URLs.

## Update rule

Update this file when vendor API clients, request patterns, image loading, or data-state patterns change.
