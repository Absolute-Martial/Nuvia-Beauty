# Vendor Portal Features and Processes

Current feature and process documentation for `vendor-portal`.

## Current role

`vendor-portal` is the seller/vendor-facing dashboard frontend. It communicates with `backend-engine` through the configured REST API endpoint.

## Current feature areas

```text
vendor dashboard UI
product/shop management
seller authentication-aware flows
order and stock overview
charts and operational summaries
localized interface support
backend API consumption
```

## Current process flow

```text
Vendor opens dashboard
  -> vendor-portal UI loads
  -> requests data from backend-engine
  -> backend returns data
  -> UI renders seller dashboards and product/shop info
```

## Current UI tooling

| Area | Packages |
|---|---|
| HTTP | Axios |
| Server state | React Query |
| Local state | Jotai |
| Forms | React Hook Form, Yup |
| Tables | rc-table, rc-pagination |
| Charts | ApexCharts, react-apexcharts |
| i18n | next-i18next, i18next, react-i18next |
| Styling | Tailwind CSS, classnames, tailwind-merge |

## Planned features

Future seller features should be documented here only after implementation or clearly marked as planned.

Potential planned areas:

```text
storage health view
provider mode control
failed task list
quota overview
product mapping tools
```

## Current Phase 4 vendor support

Phase 4 now ships a minimal vendor beauty-mapping editor inside the existing product edit flow.

Current vendor-facing position:

- vendor product edit screens can view and save beauty mapping tags for owned products
- vendor beauty mapping status now distinguishes `missing mapping`, `partial mapping`, and `ready for recommendation`
- backend ownership checks still gate `/api/v1/beauty/product-mappings`
- no storage credentials, media secrets, or provider logic are exposed to the vendor frontend
- advanced moderation, approval, or seller scoring workflows remain out of scope for this phase

## Maintenance rule

Update this file when vendor-facing features, seller processes, or API consumption flow changes.
