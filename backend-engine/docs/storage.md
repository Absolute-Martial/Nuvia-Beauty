# Backend Storage

Current storage documentation for `backend-engine` on the `development` branch.

## Current status

Phase 4 storage foundation is implemented in:

```text
backend-engine/config/filesystems.php
backend-engine/app/Domains/Storage/
backend-engine/routes/api/v1/storage.php
```

The backend now uses a provider-neutral S3-compatible contract while keeping the older AIStor/AWS variables as compatibility fallbacks.

## Current environment contract

Primary storage variables:

```env
STORAGE_DRIVER=s3_compatible
S3_PROVIDER=minio_aistor
S3_ENDPOINT=
S3_REGION=us-east-1
S3_ACCESS_KEY_ID=
S3_SECRET_ACCESS_KEY=
S3_USE_PATH_STYLE_ENDPOINT=true
S3_PUBLIC_BUCKET=nuvia-public
S3_BEAUTY_INPUTS_BUCKET=nuvia-private-beauty-inputs
S3_BEAUTY_RESULTS_BUCKET=nuvia-private-beauty-results
S3_BEAUTY_CALIBRATION_BUCKET=nuvia-private-calibration
S3_UPLOAD_URL_TTL_MINUTES=15
S3_DOWNLOAD_URL_TTL_MINUTES=60
```

Compatibility fallbacks still supported by config:

```text
AISTOR_ENDPOINT
AISTOR_ACCESS_KEY_ID
AISTOR_SECRET_ACCESS_KEY
AISTOR_REGION
AISTOR_BUCKET
AISTOR_PUBLIC_URL
AISTOR_USE_PATH_STYLE_ENDPOINT
AWS_ACCESS_KEY_ID
AWS_SECRET_ACCESS_KEY
AWS_DEFAULT_REGION
AWS_BUCKET
AWS_ENDPOINT
AWS_URL
```

## Current disks

| Disk | Visibility | Current purpose |
|---|---|---|
| `s3` | public | compatibility/public disk |
| `s3_public` | public | product/shop/public media assets |
| `s3_beauty_inputs` | private | customer or session-owned beauty input uploads |
| `s3_beauty_results` | private | generated beauty results |
| `s3_beauty_calibration` | private | opt-in calibration assets |

Rules:

- public disk visibility stays `public`
- beauty disks stay `private`
- endpoint and path-style behavior come from environment
- no bucket names or hostnames are hardcoded in controller logic

## Current API flow

### Upload slot

Route:

```http
POST /api/v1/storage/upload-slots
```

Behavior:

1. authenticates actor
2. validates purpose, MIME type, size, and owner context
3. creates a `beauty_media_assets` row with `pending_upload`
4. generates a short-lived presigned PUT URL
5. returns object metadata and upload instructions

### Confirm upload

Route:

```http
POST /api/v1/storage/media/{mediaId}/confirm
```

Behavior:

1. authorizes the actor with `MediaAssetPolicy`
2. verifies the object exists on the selected disk
3. marks the media record `confirmed`

### Signed download

Route:

```http
GET /api/v1/storage/media/{mediaId}/download-url
```

Behavior:

1. authorizes the actor
2. creates a short-lived presigned GET URL
3. never returns storage credentials
4. never exposes a permanent private object URL

### Delete or discard

Route:

```http
DELETE /api/v1/storage/media/{mediaId}
```

Behavior:

1. authorizes the actor
2. marks the row `discarded`
3. dispatches `DeleteExpiredMediaAssets`

## Current object key strategy

Object keys are generated server-side.

Current prefixes:

```text
public/products/{shop_id}/{owner_id}/{uuid}.{ext}
beauty/inputs/{shop_id}/{session_id}/{media_id}/source.{ext}
beauty/results/{shop_id}/{session_id}/{media_id}/result.{ext}
beauty/calibration/{profile_or_owner_id}/{media_id}/calibration.{ext}
```

The backend does not trust the original file name as object identity.

## Current security rules

Browser code must never receive:

- S3 or AIStor access keys
- root credentials
- provider keys
- long-lived private object URLs

Protected behavior stays backend-only:

- disk selection
- object key generation
- ownership checks
- signed URL generation
- deletion orchestration

## Current limitations

Implemented now:

- metadata creation
- presigned upload
- object existence confirmation
- signed private download
- discard and delete job

Not yet implemented:

- scheduler wiring for periodic expired-media cleanup
- audit event table for media access
- bucket lifecycle policies managed from code

## Local validation note

For local Phase 3 validation, the checked-in dev compose stack uses a MinIO-compatible
S3 endpoint and a clean dev data path. This avoids reusing incompatible persisted AIStor
state while keeping the backend contract S3-compatible.
