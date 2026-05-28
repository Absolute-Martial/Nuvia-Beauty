# Admin Panel Features and Processes

Documentation for the current `admin-panel` service.

## Current role

`admin-panel` is the platform administration frontend. It is a browser-facing Next.js app that communicates with `backend-engine` through the configured REST API endpoint.

## Current feature areas

```text
admin dashboard UI
management tables
forms and validation
charts and summaries
localized interface support
backend API consumption
```

## Current process flow

```text
Admin user opens panel
  -> panel reads configured backend API endpoint
  -> panel requests data from backend
  -> backend handles protected work
  -> panel renders result in tables, forms, or charts
```

## Current UI tooling

| Area | Packages |
|---|---|
| HTTP | Axios |
| Server state | React Query |
| Local state | Jotai |
| Forms | React Hook Form, Yup |
| Tables | rc-table, rc-pagination |
| Charts | ApexCharts |
| i18n | next-i18next, i18next, react-i18next |

## Planned feature notes

Future admin features should be documented here only after implementation or clearly marked as planned.

Potential planned areas:

```text
storage health view
provider mode control
failed task list
quota overview
product mapping tools
```

## Current Phase 4 mapping support

Phase 4 now adds a minimal admin beauty mapping workflow across the existing product edit flow and a lightweight overview screen under product management.

Current support:

- admin product edit screens can view and save beauty mapping tags
- admin beauty mapping overview lists mapped and unmapped products
- overview rows show mapping status as `missing mapping`, `partial mapping`, or `ready for recommendation`
- admin can trigger product signal recompute for the current product
- admin can trigger recompute for all mapped products from the overview surface
- backend product mapping APIs remain the source of truth under `/api/v1/beauty/product-mappings`
- admin overview reads `/api/v1/admin/beauty/product-mappings/overview`
- backend product signal recompute remains admin-only under `/api/v1/admin/beauty/recommendations/recompute`

## Maintenance rule

Update this file whenever a new admin process or feature category is added, removed, or renamed.
