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

## Console bootstrap note

The `settings` table remains the source of truth for commerce settings.

To keep Laravel console commands bootable against a fresh or partially initialized database, console startup now uses an in-memory fallback settings object when the `settings` table is unavailable during boot. The fallback reuses the same default payload as `SettingsSeeder`.

This specifically unblocks:

- `php artisan migrate`
- `php artisan route:list --path=api/v1`
- `php artisan list | grep beauty`

It does not replace seeded settings rows, and it does not change normal HTTP/runtime resolution of DB-backed settings.

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

### `beauty_profiles`

Migration:

```text
database/migrations/2026_05_28_000006_create_beauty_profiles_table.php
```

Purpose:

- stores seller consultation profile data separately from commerce `user_profiles`
- allows guest, new-customer, and returning-customer beauty criteria to be persisted without storing raw media

Current fields:

```text
id
shop_id
customer_id nullable
user_profile_id nullable
source
customer_name nullable
contact_email nullable
contact_phone nullable
skin_type_tags JSON nullable
tone_tags JSON nullable
undertone_tags JSON nullable
concern_tags JSON nullable
ingredient_tags JSON nullable
avoid_tags JSON nullable
notes nullable
created_at
updated_at
```

### `beauty_profile_snapshots`

Migration:

```text
database/migrations/2026_05_28_000007_create_beauty_profile_snapshots_table.php
```

Purpose:

- freezes the consultation criteria used by a specific session
- supports later provider analysis without mutating the original profile history

### `beauty_sessions`

Migration:

```text
database/migrations/2026_05_28_000008_create_beauty_sessions_table.php
```

Purpose:

- tracks seller-assisted consultation workflow state
- links shop, consultant, optional customer context, optional media, and current snapshot

Current fields:

```text
id
public_id unique
shop_id
consultant_user_id
customer_id nullable
user_profile_id nullable
beauty_profile_id nullable
current_snapshot_id nullable
primary_media_asset_id nullable
consultation_mode
session_state
notes nullable
saved_at nullable
discarded_at nullable
failed_at nullable
created_at
updated_at
```

Current state values used by code:

```text
draft
media_uploaded
analysis_pending
analysis_completed
saved
discarded
failed
```

### `beauty_ai_tasks`

Migration:

```text
database/migrations/2026_05_28_000009_create_beauty_ai_tasks_table.php
```

Purpose:

- stores placeholder provider task metadata for future Perfect Corp integration
- allows the seller consultation foundation to track queued and completed analysis without calling the provider yet

### `beauty_analysis_results`

Migration:

```text
database/migrations/2026_05_28_000010_create_beauty_analysis_results_table.php
```

Purpose:

- stores placeholder analysis state and normalized consultation traits
- records deterministic recommendation counts against a session even before provider integration exists

### `beauty_quota_accounts`

Migration:

```text
database/migrations/2026_05_28_000011_create_beauty_quota_accounts_table.php
```

Purpose:

- creates the provider/quota accounting foundation required by the Phase 4 gate to Perfect Corp
- currently tracks per-shop `seller_consultation` usage under a placeholder provider account

### `beauty_quota_events`

Migration:

```text
database/migrations/2026_05_28_000012_create_beauty_quota_events_table.php
```

Purpose:

- records quota-impacting consultation events such as session creation, media attachment, and recommendation generation

### `audit_logs`

Migration:

```text
database/migrations/2026_05_28_000013_create_audit_logs_table.php
```

Purpose:

- stores lightweight action audit rows where practical
- current Phase 4 implementation records consultation `saved` and `discarded` actions

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

### `beauty_events`

Migration:

```text
database/migrations/2026_05_28_000004_create_beauty_events_table.php
```

Purpose:

- stores PII-safe beauty interaction events
- feeds the deterministic product signal layer

Current fields:

```text
id
customer_id nullable
profile_id nullable
session_id nullable
product_id
shop_id nullable
event_type
source_surface nullable
recommendation_id nullable
metadata_json nullable
occurred_at
created_at
updated_at
```

Supported event types:

```text
view
add_to_cart
purchase
```

### `beauty_product_signals`

Migration:

```text
database/migrations/2026_05_28_000005_create_beauty_product_signals_table.php
```

Purpose:

- stores deterministic per-product interaction aggregates
- supports future recommendation recompute and ranking adjustments

Current fields:

```text
id
product_id unique
shop_id nullable
view_count
add_to_cart_count
purchase_count
weighted_score
signal_version
last_event_at nullable
last_recomputed_at nullable
created_at
updated_at
```

Not implemented in this phase:

- recommendation recompute audit trail
- scheduled cleanup registration for expired media deletions
