# environment-variables.md

Owner: Susank Shakya

<aside>
🔑

**`docs/deployment/environment-variables.md`** · the configuration reference and secret-handling rules for every environment.

</aside>

# 1. Purpose & scope

This page is the **configuration reference** for the stack. Configuration is supplied via environment only — never committed. Keep `.env` out of version control and use templates; secrets live in the deployment environment, never in code, images, or any `NEXT_PUBLIC_*` value.

# 2. Storage (MinIO / AIStor S3)

| Variable | Example | Notes |
| --- | --- | --- |
| `STORAGE_DRIVER` | `s3_compatible` | Storage abstraction. |
| `S3_PROVIDER` | `minio_aistor` | Provider selector. |
| `S3_USE_PATH_STYLE_ENDPOINT` | `true` | Required for MinIO. |
| `S3_ENDPOINT` | `http://minio:9000` | API endpoint. |
| `S3_KEY` / `S3_SECRET` | — | Backend-only credentials; never exposed to clients. |

# 3. Services

| Service | Port |
| --- | --- |
| MySQL 8 | `:3306` |
| Redis 7.4 | `:6379` |
| backend-engine | `:8000` |
| storefront / admin / vendor | `:3003` / `:3002` / `:3004` |

# 4. Providers

| Variable | Notes |
| --- | --- |
| `AI_PROVIDER` | `perfect_corp` or `demo`. |
| `PERFECT_CORP_API_KEY` / `PERFECT_CORP_ENDPOINT` | Backend-only; never `NEXT_PUBLIC_*`. |
| `AI_DEMO_MODE` | `true` forces deterministic demo-mode. |

When provider keys are unset, the system runs in **demo-mode** automatically.

# 5. Buckets

- `nuvia-public-assets` (public) — product/catalog media only.
- `nuvia-private-beauty-inputs`, `nuvia-private-beauty-results`, `nuvia-private-calibration` (private) — never publicly reachable.

# 6. Signed URL TTLs

- Upload (PUT) 15 min · Download (GET) 60 min.

# 7. Secret handling

- Secrets stay in the environment only; rotate on schedule and on suspected exposure.
- Responses are redacted of `api_key`, `secret`, `signed_url`, and `raw_payload` (see `backend-engine/agent-guardrails.md`).

# 8. Related documentation

- Storage: `storage/storage-architecture.md`, `storage/private-bucket-policy.md`. Settings: `backend-engine/settings-system.md`. Local: [[local-setup.md](http://local-setup.md)](local-setup%20md%206abbaa6a9f474ae4b51fc1edba2ca4ac.md).