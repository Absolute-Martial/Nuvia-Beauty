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

Phase 4 does not add a full admin beauty mapping UI yet.

Current support is a controlled backend workflow:

- backend product mapping APIs exist under `/api/v1/beauty/product-mappings`
- the first 10 available products can be mapped with:

```bash
php artisan db:seed --class=Database\\Seeders\\BeautyProductMappingSeeder
```

This keeps the implementation inside the secured Laravel boundary until admin interaction flows are specified more precisely.

## Maintenance rule

Update this file whenever a new admin process or feature category is added, removed, or renamed.
