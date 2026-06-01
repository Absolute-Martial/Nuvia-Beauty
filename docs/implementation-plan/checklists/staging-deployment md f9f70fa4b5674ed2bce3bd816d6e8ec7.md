# staging-deployment.md

Owner: Susank Shakya

<aside>
📄

**`docs/implementation-plan/checklists/staging-deployment.md`**

</aside>

# Staging deployment checklist

- [ ]  Env vars set (storage driver, S3 provider, TTLs).
- [ ]  Migrations run successfully.
- [ ]  Services healthy (backend, 3 frontends, MySQL, Redis, storage).
- [ ]  Smoke test core flows.
- [ ]  Evidence stored in `evidence/staging-verification/`.