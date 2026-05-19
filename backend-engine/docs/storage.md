# Backend Storage

Storage documentation for `backend-engine` in the Nuvia Beauty `development` branch.

## Current status

Current backend filesystem configuration lives in:

```text
backend-engine/config/filesystems.php
```

Current default disk:

```php
'default' => env('FILESYSTEM_DISK', 'local')
```

Current `.env.example` value:

```env
FILESYSTEM_DISK=local
```

## Current configured disks

| Disk | Driver | Root/bucket | Visibility | Status |
|---|---|---|---|---|
| `local` | `local` | `storage_path('app')` | Private by convention | Current |
| `public` | `local` | `storage_path('app/public')` | Public | Current |
| `s3` | `s3` | `AWS_BUCKET` | Public | Current, generic/public-oriented |

Current `s3` disk variables:

```env
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_URL=
AWS_ENDPOINT=
```

These variables are not currently listed in `backend-engine/.env.example`.

## Current S3 dependency

The backend includes:

```json
"league/flysystem-aws-s3-v3": "^3.30"
```

This enables Laravel filesystem support for AWS S3 and S3-compatible providers when disks are configured.

## Current storage paths

`backend-engine/.env.example` currently includes local path-style variables:

```env
STYLEFIT_SETTINGS_PATH=storage/app/private/zyro/settings/zyro.settings.json
STYLEFIT_TRYON_SOURCE_PATH=storage/app/private/zyro/try-on/source-photos
STYLEFIT_TRYON_RESULT_PATH=storage/app/private/zyro/try-on/results
```

These are current configuration paths. They are not S3-compatible storage abstractions yet.

## Planned storage direction

Not implemented yet.

Target storage contract:

```text
Application contract: S3-compatible object storage
Preferred self-hosted provider: MinIO AIStor
Alternative provider: AWS S3 or compatible provider
```

The application should not hardcode MinIO-only logic in controllers or business workflows.

Correct direction:

```text
Controller -> Storage service -> Laravel disk -> configured S3-compatible endpoint
```

Incorrect direction:

```text
Controller -> hardcoded MinIO URL/access key/bucket
```

## Planned disk model

Not implemented yet.

Recommended Laravel disks:

```text
s3_public
s3_beauty_inputs
s3_beauty_results
s3_beauty_calibration
```

| Disk | Visibility | Purpose |
|---|---|---|
| `s3_public` | Public | Product images, shop logos, banners, public UI assets. |
| `s3_beauty_inputs` | Private | Customer/source photos for analysis workflows. |
| `s3_beauty_results` | Private | Generated result images and overlays. |
| `s3_beauty_calibration` | Private | Explicit opt-in calibration images. |

## Planned environment variables

Not implemented yet.

Recommended backend variables:

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

## Planned bucket model

Not implemented yet.

| Bucket | Visibility | Stored data |
|---|---|---|
| `nuvia-public-assets` | Public/CDN-readable | Product images, shop logos, banners, public UI assets. |
| `nuvia-private-beauty-inputs` | Private | Captured source photos for provider processing. |
| `nuvia-private-beauty-results` | Private | Try-on/result/overlay media. |
| `nuvia-private-calibration` | Private | Explicit opt-in calibration images only. |

## Planned object key format

Use generated IDs and structured prefixes.

```text
public/products/{shop_id}/{product_id}/{uuid}.{ext}
public/shops/{shop_id}/logos/{uuid}.{ext}
beauty/inputs/{shop_id}/{session_id}/{media_id}/source.{ext}
beauty/results/{shop_id}/{session_id}/{task_id}/result.{ext}
beauty/calibration/{profile_id}/{media_id}/calibration.{ext}
```

Do not use original filenames as object identity.

## Planned upload flow

Not implemented yet.

```text
1. Frontend requests upload slot from backend.
2. Backend authenticates actor and validates file purpose.
3. Backend checks MIME type, size, and ownership/session context.
4. Backend creates pending media metadata.
5. Backend returns a short-lived presigned PUT URL.
6. Browser uploads directly to S3-compatible storage.
7. Frontend confirms upload with backend.
8. Backend verifies object exists and marks media confirmed.
```

## Planned private download flow

Not implemented yet.

```text
1. Frontend requests private media access.
2. Backend checks auth and ownership.
3. Backend returns short-lived signed download URL.
4. Backend logs access without logging the signed URL.
5. Browser uses URL temporarily.
```

## Planned backend service structure

Not implemented yet.

Recommended structure:

```text
app/Domains/Storage/
├── Controllers/
├── DTO/
├── Jobs/
├── Policies/
└── Services/
```

Alternative for beauty-specific storage:

```text
app/Domains/Beauty/Storage/
├── Controllers/
├── DTO/
├── Jobs/
├── Policies/
└── Services/
```

## Security rules

Never expose the following to frontend code or API responses:

```text
AWS_ACCESS_KEY_ID
AWS_SECRET_ACCESS_KEY
S3_ACCESS_KEY_ID
S3_SECRET_ACCESS_KEY
MINIO_ROOT_USER
MINIO_ROOT_PASSWORD
Long-lived private object URLs
Provider API keys
```

Private media must not be stored in public buckets.

## Lifecycle rules

Planned retention defaults:

| Asset type | Suggested retention |
|---|---:|
| Guest source photo | 24 hours |
| Saved consultation input | 7 to 30 days |
| Result image | 30 to 180 days |
| Calibration image | Explicit opt-in, review every 90 days |
| Failed temporary artifact | 24 hours |

## Current gaps

```text
Dedicated S3-compatible disks are not implemented.
S3-compatible env variables are not yet in .env.example.
MinIO/AIStor service is not yet in Docker Compose.
Upload-slot endpoints are not implemented.
Media metadata table is not implemented.
Private signed download flow is not implemented.
```

## Update rule

Update this file when:

```text
config/filesystems.php changes
.env.example storage variables change
Docker Compose adds MinIO/AIStor
new media tables are added
storage endpoints are added
frontend upload flow changes
retention/lifecycle policy changes
```
