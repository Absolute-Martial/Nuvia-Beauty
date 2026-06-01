# frontend-test-plan.md

Owner: Susank Shakya

<aside>
🖥️

**`docs/testing/frontend-test-plan.md`** · verifying the three Next.js apps — components, integration, and accessibility.

</aside>

# 1. Purpose & scope

This plan covers the three Next.js 15 / React 19 apps: `storefront` (`:3003`), `admin-panel` (`:3002`), and `vendor-portal` (`:3004`). The frontends are thin clients, so tests focus on correct rendering of backend payloads, robust loading/error/empty states, and accessible, consent-first interactions. API calls are mocked; no test holds credentials.

# 2. What we test

| App | Focus |
| --- | --- |
| Storefront | Recommendation UI (reasons + warnings), confidence labels, consent prompts, self-scan capture states. |
| Admin-panel | Product management + settings forms, validation, audit views render (no media / full signed URLs). |
| Vendor-portal | Consultation flow, product mapping, recommendation review. |
| Cross-cutting | Loading/error/empty states; i18n strings; graceful demo-mode. |
| Accessibility | Key flows keyboard-navigable; confidence conveyed by label + shape, not color alone; consent explicit. |

# 3. Tooling

- Component/integration tests per workspace (`@nuvia/storefront|admin-panel|vendor-portal`), with a mocked API layer.

# 4. Commands

```
yarn workspace @nuvia/storefront test
yarn workspace @nuvia/admin-panel test
yarn workspace @nuvia/vendor-portal test
```

# 5. Coverage & gates

- Each app's primary flow has an integration test covering success + error + empty.
- No component renders raw provider data, private media, or full signed URLs.
- Accessibility checks pass on key flows.

# 6. Evidence

- Store output under `implementation-plan/evidence/frontend-tests/`.

# 7. Limitations & future enhancements

- Full cross-service journeys are covered in `e2e-test-plan.md`; visual-regression testing is a future enhancement.

# 8. Related documentation

- Storefront: `storefront/`. Admin: `admin-panel/`. Vendor: `vendor-portal/`. E2E: [[e2e-test-plan.md](http://e2e-test-plan.md)](e2e-test-plan%20md%20c7de9b248c374aa099193e228167e250.md).