# local-setup.md

Owner: Susank Shakya

<aside>
💻

**`docs/deployment/local-setup.md`** · run the full Nuvia Beauty stack locally in demo-mode.

</aside>

# 1. Purpose & scope

This page gets a developer from a clean checkout to a **fully running stack in demo-mode** — no live provider or storage credentials required. It is the baseline environment for development and for running the test suites.

# 2. Prerequisites

- Docker + Docker Compose.
- Yarn 1.22.x and Node 24.15 (for frontend workspaces).
- A clone of the repository at the target branch.

# 3. Steps

1. Copy env templates and set demo-mode values (see [[environment-variables.md](http://environment-variables.md)](environment-variables%20md%20559e80250b104a79b8f50e0ef8a3aa6e.md)):

```
cp .env.example .env
```

1. Start the stack:

```
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```

1. Backend setup (migrate + seed demo data):

```
php artisan migrate --seed
```

1. Install and run the frontends (Yarn workspaces):

```
yarn install
yarn workspace @nuvia/storefront dev
```

# 4. Verify

| Check | Expected |
| --- | --- |
| Services up | storefront `:3003`, admin `:3002`, vendor `:3004`, backend `:8000`, MinIO console `:9001`. |
| Buckets created | `nuvia-public-assets`, `nuvia-private-beauty-inputs`, `nuvia-private-beauty-results`, `nuvia-private-calibration`. |
| Demo journey | A consented self-scan flow completes via demo-mode. |

# 5. Troubleshooting

- **Buckets missing** → re-run the storage bootstrap step; confirm `S3_USE_PATH_STYLE_ENDPOINT=true`.
- **Queues idle** → ensure a queue worker is running; check Redis connectivity on `:6379`.
- **Frontend cannot reach API** → verify the backend base URL and that `:8000` is healthy.

# 6. Related documentation

- Config: [[environment-variables.md](http://environment-variables.md)](environment-variables%20md%20559e80250b104a79b8f50e0ef8a3aa6e.md). Tests: `testing/README.md`. Staging: [[staging-deployment.md](http://staging-deployment.md)](staging-deployment%20md%200c15e6899cab4bc5ba37bca6571938e3.md).