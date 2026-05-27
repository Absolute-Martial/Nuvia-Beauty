# Backend Database

Current database documentation for `backend-engine` on the `development` branch.

## Current database runtime

Primary runtime:

```text
MySQL 8.0
```

Current backend connection pattern:

```text
backend-engine -> Laravel models/services -> MySQL
```

Frontend apps must not connect to the database directly.

## Current Phase 4 tables

### `beauty_media_assets`

Migration:

```text
database/migrations/2026_05_28_000001_create_beauty_media_assets_table.php
```

Purpose:

- stores upload ownership and object metadata only
- does not store raw file bytes

Current fields:

```text
id
session_id nullable
profile_id nullable
shop_id nullable
owner_type
owner_id
asset_type
storage_provider
disk_name
bucket
object_key
object_version nullable
content_type
size_bytes
checksum_sha256 nullable
visibility
status
expires_at nullable
discarded_at nullable
deleted_at nullable
created_at
updated_at
```

Current indexes:

```text
session_id
profile_id
shop_id
status
owner_type + owner_id
disk_name + bucket
expires_at
```

Current status values used by code:

```text
pending_upload
confirmed
discarded
expired
deleted
delete_failed
```

### `beauty_product_mappings`

Migration:

```text
database/migrations/2026_05_28_000002_create_beauty_product_mappings_table.php
```

Purpose:

- stores beauty-oriented product matching metadata
- remains attached to existing commerce `products`

Current fields:

```text
id
product_id
concern_tags JSON nullable
skin_type_tags JSON nullable
tone_tags JSON nullable
undertone_tags JSON nullable
ingredient_tags JSON nullable
avoid_tags JSON nullable
explanation_template nullable
created_at
updated_at
```

Current constraint:

```text
product_id is unique
```

### `beauty_recommendations`

Migration:

```text
database/migrations/2026_05_28_000003_create_beauty_recommendations_table.php
```

Purpose:

- stores recommendation outputs produced by the deterministic scoring service
- supports customer/profile/session retrieval without exposing provider logic

Current fields:

```text
id
customer_id nullable
profile_id nullable
session_id nullable
product_id
score
confidence
reasons_json
warnings_json
breakdown_json
score_version
accepted nullable
dismissed nullable
created_at
updated_at
```

## Existing model integration

Phase 4 intentionally attaches to existing Marvel commerce models instead of replacing them.

Current linked models:

- `Marvel\Database\Models\Product`
- `Marvel\Database\Models\Shop`
- `Marvel\Database\Models\Profile`
- `Marvel\Database\Models\User`

This keeps:

- product ownership in current commerce tables
- shop ownership in existing `shops.owner_id` and `shops.staffs`
- customer profile ownership in existing `user_profiles.customer_id`

## Controlled seed workflow

Current seed helper:

```text
database/seeders/BeautyProductMappingSeeder.php
```

Behavior:

- reads the first 10 existing products
- assigns deterministic beauty mapping presets
- exits cleanly if no products exist

Current command:

```bash
php artisan db:seed --class=Database\\Seeders\\BeautyProductMappingSeeder
```

This is a controlled seed workflow. It was not forced into the global database seed path because product ownership and catalog shape already belong to the commerce package.

## Sensitive data rules

Never store:

- raw media bytes in MySQL
- S3/AIStor access keys
- long-lived private media URLs
- frontend-visible provider credentials

Store instead:

- object metadata
- ownership references
- signed URL TTL outputs
- deterministic recommendation outputs

## Follow-up items

Not implemented in this phase:

- `beauty_events`
- `beauty_product_signals`
- recommendation recompute audit trail
- scheduled cleanup registration for expired media deletions
