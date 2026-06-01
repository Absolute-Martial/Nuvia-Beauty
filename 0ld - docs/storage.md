# Storage Architecture

Root storage architecture notes for Phase 4.

## Current implementation

Nuvia Beauty now uses a backend-owned S3-compatible storage contract implemented in Laravel.

Implemented surfaces:

- provider-neutral S3 environment variables in `backend-engine/.env.example`
- explicit public and private disks in `backend-engine/config/filesystems.php`
- storage metadata in `beauty_media_assets`
- signed upload and download APIs under `/api/v1/storage/*`

## Public and private separation

Current storage split:

| Disk | Visibility | Purpose |
|---|---|---|
| `s3_public` | public | product and other public assets |
| `s3_beauty_inputs` | private | uploaded customer/source beauty media |
| `s3_beauty_results` | private | generated recommendation or beauty result media |
| `s3_beauty_calibration` | private | calibration-only media |

Rules:

- browser uploads go through short-lived signed URLs
- browser downloads for private media go through short-lived signed URLs
- storage credentials stay backend-only
- media bytes are never stored in MySQL

## Current API contract

Implemented routes:

```text
POST   /api/v1/storage/upload-slots
POST   /api/v1/storage/media/{mediaId}/confirm
GET    /api/v1/storage/media/{mediaId}/download-url
DELETE /api/v1/storage/media/{mediaId}
```

These APIs are owned by `backend-engine`. Frontend apps consume them through REST only.

## Provider strategy

Current contract:

```text
S3-compatible first
```

This allows:

- MinIO AIStor
- AWS S3
- other S3-compatible providers

without hardcoding provider-specific controller logic into the browser or frontend apps.

## Current follow-up items

Not implemented yet:

- scheduled expired-media cleanup registration
- storage operations dashboard UI in admin or vendor apps
