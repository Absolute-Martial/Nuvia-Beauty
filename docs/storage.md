# Storage Architecture

Current and target storage documentation for the Nuvia Beauty `development` branch.

## Current storage state

The backend currently has Laravel filesystem configuration in:

```text
backend-engine/config/filesystems.php
```

Current configured disks:

| Disk | Driver | Visibility | Notes |
|---|---|---|---|
| `local` | `local` | private by default | Uses `storage_path('app')`. |
| `public` | `local` | public | Uses `storage_path('app/public')` and `APP_URL/storage`. |
| `s3` | `s3` | public | Uses `AWS_*` variables and is currently public-oriented. |

Current default disk:

```php
'default' => env('FILESYSTEM_DISK', 'local')
```

Current backend `.env.example` default:

```env
FILESYSTEM_DISK=local
```

## Current storage dependency state

The backend now includes the Flysystem S3 adapter dependency:

```json
"league/flysystem-aws-s3-v3": "^3.30"
```

This enables Laravel S3 filesystem disks for AWS S3 and S3-compatible storage providers such as MinIO or MinIO AIStor.

## Target storage direction

Use this rule:

```text
Application contract: S3-compatible storage
Preferred self-hosted provider: MinIO AIStor
Fallback/alternative providers: AWS S3 or another S3-compatible object store
```

The application must not hardcode MinIO-only assumptions in business logic.

Correct abstraction:

```text
Laravel storage service -> S3-compatible disk -> configured provider endpoint
```

Incorrect abstraction:

```text
Controller/business logic -> hardcoded MinIO endpoint
```

## Planned bucket model

| Bucket env | Default bucket name | Visibility | Purpose |
|---|---|---|---|
| `S3_PUBLIC_BUCKET` | `nuvia-public-assets` | Public/CDN-readable | Product images, shop logos, banners, public UI assets. |
| `S3_BEAUTY_INPUTS_BUCKET` | `nuvia-private-beauty-inputs` | Private | Captured source photos for analysis/try-on workflows. |
| `S3_BEAUTY_RESULTS_BUCKET` | `nuvia-private-beauty-results` | Private | Generated result images, overlays, before/after media. |
| `S3_BEAUTY_CALIBRATION_BUCKET` | `nuvia-private-calibration` | Private | Explicit opt-in calibration images only. |

## Planned object key format

Use deterministic prefixes and generated identifiers. Do not use raw original filenames as storage identity.

```text
public/products/{shop_id}/{product_id}/{uuid}.{ext}
public/shops/{shop_id}/logos/{uuid}.{ext}
beauty/inputs/{shop_id}/{session_id}/{media_id}/source.{ext}
beauty/results/{shop_id}/{session_id}/{task_id}/result.{ext}
beauty/calibration/{profile_id}/{media_id}/calibration.{ext}
```

## Planned backend environment variables

Provider-neutral variables:

```env
STORAGE_DRIVER=s3_compatible
S3_PROVIDER=minio_aistor
S3_ENDPOINT=http://minio:9000
S3_REGION=us-east-1
S3_ACCESS_KEY_ID=
S3_SECRET_ACCESS_KEY=
S3_USE_PATH_STYLE_ENDPOINT=true

S3_PUBLIC_BUCKET=nuvia-public-assets
S3_BEAUTY_INPUTS_BUCKET=nuvia-private-beauty-inputs
S3_BEAUTY_RESULTS_BUCKET=nuvia-private-beauty-results
S3_BEAUTY_CALIBRATION_BUCKET=nuvia-private-calibration

S3_UPLOAD_URL_TTL_MINUTES=15
S3_DOWNLOAD_URL_TTL_MINUTES=60
S3_SERVER_SIDE_ENCRYPTION=AES256
```

For AWS compatibility, these can also be mapped to existing `AWS_*` Laravel conventions where useful. The service code should read from one normalized config layer, not directly from scattered environment variables.

## Planned Laravel disks

The single public-oriented `s3` disk should be replaced or supplemented with explicit disks:

```text
s3_public
s3_beauty_inputs
s3_beauty_results
s3_beauty_calibration
```

Expected disk responsibilities:

| Disk | Visibility | Access pattern |
|---|---|---|
| `s3_public` | public | Public product/shop/media assets. |
| `s3_beauty_inputs` | private | Presigned upload only, worker/backend read only. |
| `s3_beauty_results` | private | Worker write, backend-authorized signed read. |
| `s3_beauty_calibration` | private | Explicit opt-in, strict ownership checks. |

## Upload data flow

Target browser upload flow:

```text
1. Frontend asks backend for an upload slot.
2. Backend validates actor, session, MIME type, size, and quota/purpose.
3. Backend creates a pending media record.
4. Backend returns a short-lived presigned PUT URL.
5. Browser uploads directly to S3-compatible storage.
6. Browser calls backend confirm endpoint.
7. Backend verifies the object exists and marks media as confirmed.
8. Worker/provider flow consumes the object through backend credentials.
```

## Private download data flow

Target private media read flow:

```text
1. Frontend asks backend for a media download URL.
2. Backend checks authentication and ownership/authorization.
3. Backend writes an audit record without logging the signed URL.
4. Backend returns a short-lived signed download URL.
5. Browser reads the object using that temporary URL.
```

## Media metadata model

Media records should store metadata only, never file bytes.

Recommended table:

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

## Status lifecycle

Recommended media asset states:

```text
pending_upload
uploaded
confirmed
discarded
expired
deleted
delete_failed
```

Example lifecycle:

```text
pending_upload -> uploaded -> confirmed -> expired -> deleted
pending_upload -> uploaded -> discarded -> deleted
confirmed -> delete_failed -> deleted
```

## Security rules

Non-negotiable rules:

```text
No S3/MinIO/AIStor root credentials in frontend code.
No storage access keys in NEXT_PUBLIC_* variables.
No permanent public URL for private beauty inputs/results/calibration images.
No direct private bucket read without backend authorization.
No controller-level storage implementation details.
No original filename as trusted object identity.
No raw image bytes in MySQL.
No long-lived signed URLs.
```

## Public vs private media

Public assets:

```text
Product images
Shop logos
Banners
Public UI assets
```

Private assets:

```text
Customer face/source photos
AI/try-on result media
Calibration images
Provider intermediate files
```

Private assets must use short-lived signed URLs and backend ownership checks.

## CORS direction

For browser direct uploads, S3-compatible storage must allow only required methods and origins.

Recommended methods:

```text
PUT
GET
HEAD
```

Recommended allowed headers:

```text
Content-Type
Content-Length
Content-MD5
x-amz-content-sha256
x-amz-date
x-amz-security-token
```

Allowed origins should be environment-specific:

```text
http://localhost:3002
http://localhost:3003
http://localhost:3004
https://admin.example.com
https://www.example.com
https://vendor.example.com
```

Do not use wildcard origins for production private uploads.

## MinIO / AIStor deployment direction

Local/staging may use a MinIO-compatible container. Production can use MinIO AIStor on a private server or cluster.

Application configuration must stay provider-neutral:

```env
S3_ENDPOINT=https://s3.example.com
S3_PROVIDER=minio_aistor
S3_USE_PATH_STYLE_ENDPOINT=true
```

The backend and workers need network access to the S3 endpoint. The MinIO/AIStor console should not be public. Protect it with private networking, VPN, Tailscale, or Zero Trust access.

## Lifecycle and deletion

Private buckets need lifecycle controls.

Recommended defaults:

| Asset type | Suggested expiry |
|---|---:|
| Guest source photo | 24 hours |
| Saved consultation input | 7 to 30 days, configurable |
| Result image | 30 to 180 days, configurable |
| Calibration image | Explicit opt-in, review every 90 days |
| Failed temporary artifact | 24 hours |

Deletion must be handled by backend jobs and provider lifecycle rules where available.

## Implementation boundary

Controllers should only validate request shape and delegate.

Correct backend layering:

```text
Controller
  -> MediaAssetService
    -> S3CompatibleStorageService
      -> Laravel Storage disk
```

The service layer owns:

```text
bucket selection
object key generation
presigned URL creation
metadata write
ownership check
delete/discard flow
audit logging
```

## Current gap list

As of this documentation state:

- `backend-engine/config/filesystems.php` still has one public-oriented `s3` disk.
- `.env.example` still defaults to `FILESYSTEM_DISK=local`.
- Dedicated public/private S3-compatible disks are not yet implemented.
- MinIO/AIStor Docker Compose service is not yet implemented.
- Backend upload slot endpoints are not yet implemented.
- Media asset table is not yet implemented.
- Frontend upload flows are not yet migrated to presigned upload flow.

## Acceptance criteria for future implementation

Storage upgrade is complete only when:

```text
Local dev can upload to S3-compatible storage through a presigned URL.
Private beauty objects are not publicly readable.
Public assets are CDN/public-bucket ready.
Backend can swap MinIO/AIStor to AWS S3 by environment variables.
No S3/MinIO/AIStor credentials appear in browser bundle or API responses.
Expired and discarded media can be deleted by backend jobs.
```
