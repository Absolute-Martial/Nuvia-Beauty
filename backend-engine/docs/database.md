# Backend Database

Database documentation for `backend-engine` in the Nuvia Beauty `development` branch.

## Scope

The backend owns marketplace data, users, shops, products, product metadata,
orders, and legacy settings tables.

## Product metadata

Try-on metadata is stored through product metadata where supported:

- `tryOnEnabled`
- `vtoType`
- `garmentType`
- `garmentReferenceImage`
- `bodyZone`
- `styleTags`

## Current database service

The Docker Compose database service is MySQL.

| Field | Current value |
|---|---|
| Compose service | `db` |
| Image | `mysql:8.0` |
| Host port | `3306` |
| Container data volume | `db_data:/var/lib/mysql` |
| Backend DB host in Compose | `db` |

## Current Compose database variables

Root `docker-compose.yml` configures MySQL with:

```env
MYSQL_DATABASE=${DB_DATABASE:-nuvia_beauty}
MYSQL_USER=${DB_USERNAME:-nuvia_beauty}
MYSQL_PASSWORD=${DB_PASSWORD:-nuvia_beauty}
MYSQL_ROOT_PASSWORD=${DB_ROOT_PASSWORD:-nuvia_beauty_root}
MYSQL_ALLOW_EMPTY_PASSWORD=no
```

The backend connects with:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=${DB_DATABASE:-nuvia_beauty}
DB_USERNAME=${DB_USERNAME:-nuvia_beauty}
DB_PASSWORD=${DB_PASSWORD:-nuvia_beauty}
```

## Current `.env.example` database defaults

`backend-engine/.env.example` currently uses:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stylefit
DB_USERNAME=stylefit
DB_PASSWORD=
DB_SOCKET=
```

This is different from Docker Compose production-like defaults. Keep this difference visible when debugging local database connection issues.

## Data ownership rule

Only `backend-engine` should access MySQL.

Correct:

```text
Frontend -> backend API -> MySQL
```

Incorrect:

```text
Frontend -> MySQL
```

## Current persistence ownership

Current backend package dependency includes:

```text
marvel/shop dev-main
```

The local `marvel/shop` package likely owns much of the commerce data model. New Nuvia-specific backend tables should be documented here when added.

## Migration rules

Use Laravel migrations for schema changes.

Rules:

```text
Every new table must have a migration.
Every schema change must be reversible where practical.
Do not modify production data manually as the primary migration strategy.
Do not store raw file bytes in MySQL.
Store object metadata only for uploaded files.
```

## Planned media metadata table

Not implemented yet.

Recommended future table:

```text
beauty_media_assets
```

Recommended fields:

```text
id
session_id
profile_id
shop_id
owner_type
owner_id
asset_type
storage_provider
disk_name
bucket
object_key
object_version
content_type
size_bytes
checksum_sha256
visibility
encryption_mode
status
expires_at
discarded_at
deleted_at
created_at
updated_at
```

Purpose:

```text
Store file metadata and ownership only. Never store image/file bytes.
```

## Planned beauty/domain tables

Not implemented yet.

Future beauty module tables may include:

```text
beauty_profiles
beauty_profile_snapshots
beauty_sessions
beauty_media_assets
beauty_ai_tasks
beauty_analysis_results
beauty_product_mappings
beauty_recommendations
beauty_product_effect_logs
beauty_quota_accounts
beauty_quota_events
audit_logs
```

Do not treat these as current tables until migrations exist in the repository.

## Transaction rule

Use database transactions when multiple database changes must succeed or fail together.

Examples:

```text
reserve quota + create AI task
store provider result + mark task complete
create media metadata + create audit event
mark media discarded + queue delete job record
```

Do not hold a database transaction open during long external calls:

```text
object storage uploads
provider API calls
slow HTTP requests
queue worker polling
```

## Indexing rules

New tables should define indexes around actual query paths.

Likely indexes for future media/task tables:

```text
owner_type + owner_id
session_id
profile_id
shop_id
status
expires_at
provider_task_id
created_at
```

## Sensitive data rule

Avoid storing secrets or high-risk raw data in database tables.

Do not store:

```text
S3 access keys
MinIO/AIStor root credentials
Provider API keys
Full private signed URLs
Raw image bytes
Unredacted provider payloads containing sensitive private data
```

Store references instead:

```text
media_id
bucket
object_key
object_version
provider_task_id
normalized result JSON
status
error code
```

## Documentation update rule

Update this file whenever:

```text
new migrations are added
new domain tables are added
schema ownership changes
database environment defaults change
retention/deletion rules change
media metadata structure changes
```
