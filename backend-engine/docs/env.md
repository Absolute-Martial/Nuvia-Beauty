# Backend Engine Environment Variables

| Name | Purpose | Safe for frontend | Required | Example |
| --- | --- | --- | --- | --- |
| `APP_NAME` | Laravel app label | No | Yes | `Nuvia Beauty` |
| `APP_ENV` | Runtime environment | No | Yes | `local` |
| `APP_KEY` | Laravel encryption key | No | Yes | generated |
| `APP_DEBUG` | Debug mode | No | Yes | `true` locally |
| `APP_URL` | Backend URL | Yes | Yes | `http://localhost:8000` |
| `DB_CONNECTION` | Database driver | No | Yes | `mysql` |
| `DB_HOST` | Database host | No | Yes | `127.0.0.1` |
| `DB_PORT` | Database port | No | Yes | `3306` |
| `DB_DATABASE` | Database name | No | Yes | `nuvia_beauty` |
| `DB_USERNAME` | Database user | No | Yes | `nuvia_beauty` |
| `DB_PASSWORD` | Database password | No | Yes | empty local only |
| `DB_SOCKET` | Optional MySQL socket | No | No | `/run/mysqld/mysqld.sock` |
| `CACHE_DRIVER` | Cache backend | No | Yes | `file` local, `redis` production |
| `QUEUE_CONNECTION` | Queue backend | No | Yes | `sync` local, `redis` production |
| `SESSION_DRIVER` | Session backend | No | Yes | `file` local |
| `SANCTUM_STATEFUL_DOMAINS` | Trusted SPA domains | No | Yes | `localhost:3003,localhost:3002` |
| `FRONTEND_URL` | Storefront URL | Yes | Yes | `http://localhost:3003` |
| `ADMIN_URL` | Admin URL | No | Yes | `http://localhost:3002` |
| `VENDOR_URL` | Vendor portal URL | No | Yes | `http://localhost:3004` |
| `FILESYSTEM_DISK` | Default storage disk | No | Yes | `local` |
| `FILESYSTEM_CLOUD` | Default cloud disk | No | No | `s3` |
| `IMPORT_FILESYSTEM_DISK` | Disk used for CSV imports | No | No | `s3` |
| `MEDIA_DISK` | Disk used for media library uploads | No | No | `public` or `s3` |
| `AISTOR_ACCESS_KEY_ID` | AIStor S3 access key | No | Yes for object storage | secret |
| `AISTOR_SECRET_ACCESS_KEY` | AIStor S3 secret | No | Yes for object storage | secret |
| `AISTOR_REGION` | AIStor region label | No | Yes for object storage | `us-east-1` |
| `AISTOR_BUCKET` | AIStor bucket name | No | Yes for object storage | `nuvia-public` |
| `AISTOR_PUBLIC_URL` | Public object URL base | Yes | No | `https://assets.example.com` |
| `AISTOR_ENDPOINT` | Internal AIStor API endpoint | No | Yes for object storage | `http://aistor:9000` |
| `AISTOR_USE_PATH_STYLE_ENDPOINT` | Path-style object URLs | No | No | `true` |
| `AISTOR_BUCKET_ENDPOINT` | Bucket-hostname style routing | No | No | `false` |
| `AISTOR_ROOT_PREFIX` | Optional bucket prefix | No | No | `uploads` |
| `YOUCAM_API_BASE_URL` | Perfect Corp API base URL | No | Yes for try-on | `https://yce-api-01.makeupar.com` |
| `YOUCAM_API_KEY` | Perfect Corp API key | No | Yes for try-on | secret |
| `YOUCAM_API_BEARER_KEY` | Optional alternate bearer token | No | No | secret |
| `NUVIA_SETTINGS_PATH` | Private settings JSON path | No | Yes | `storage/app/private/nuvia/settings/nuvia.settings.json` |
| `NUVIA_TRYON_SOURCE_PATH` | Private source photo path | No | Yes | `storage/app/private/nuvia/try-on/source-photos` |
| `NUVIA_TRYON_RESULT_PATH` | Private try-on result path | No | Yes | `storage/app/private/nuvia/try-on/results` |
| `FLAGS_ENABLED` | Feature flag toggle | No | No | `false` |
| `FLAGS_PROVIDER` | Feature flag provider | No | No | `local` |
| `SENTRY_LARAVEL_DSN` | Sentry DSN | No | No | secret |

## Rules

- Do not commit real API keys, passwords, tokens, MinIO secrets, Cloudflare
  tokens, Flagsmith server keys, or YouCam keys.
- Frontend apps receive only URLs and browser-safe public values.
