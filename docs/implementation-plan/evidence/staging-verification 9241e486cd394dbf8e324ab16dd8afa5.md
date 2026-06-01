# staging-verification/

Owner: Susank Shakya

<aside>
📁

**`docs/implementation-plan/evidence/staging-verification/`** — evidence that staging deployments were verified.

</aside>

## 2026-06-01 — Phase 3 staging smoke

Commands and results:

- API root on the current staging domain: HTTP 200.
- API `/products` on the current staging domain: HTTP 200, but returned an empty product collection.
- Storefront root on the current staging domain: HTTP 200.
- Admin root on the current staging domain: HTTP 307 redirect.
- Vendor root on the current staging domain: HTTP 307 redirect.

Go/no-go:

- Basic staging entrypoints respond.
- Full staging go is not recorded yet because the deployed catalog was empty and the end-to-end storefront recommendation flow was not proven against deployed data.
- The current branch now includes production-safe demo seeding and a deploy smoke script.
- Re-run after redeploying the current branch with:
  - `BEAUTY_DEMO_ALLOW_PRODUCTION=true`
  - `BEAUTY_DEMO_AUTO_PREPARE=true`
  - deterministic `BEAUTY_DEMO_OWNER_PASSWORD` and `BEAUTY_DEMO_ADMIN_PASSWORD`
- Then run `bash scripts/phase6-deployment-smoke.sh` and archive the resulting JSON report.
