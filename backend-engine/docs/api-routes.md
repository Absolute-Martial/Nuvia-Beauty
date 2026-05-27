# Backend API Routes

Current backend route documentation for `backend-engine` on the `development` branch.

## Scope

This file covers:

- app-level routes in `backend-engine/routes/api.php`
- the new Phase 4 domain route files under `backend-engine/routes/api/v1/`

Package-owned commerce routes registered by `marvel/shop` still exist outside these files and are not re-listed here.

## Current app-level route includes

`backend-engine/routes/api.php` now owns:

```text
GET  /api/user
/api/v1/storage/*
/api/v1/beauty/*
```

The `/api/v1` includes are loaded from:

```text
backend-engine/routes/api/v1/storage.php
backend-engine/routes/api/v1/beauty.php
```

## Current routes

### Core authenticated route

| Method | Path | Middleware | Status |
|---|---|---|---|
| `GET` | `/api/user` | `auth:api` | current |

### Storage routes

Owner domain:

```text
App\Domains\Storage
```

| Method | Path | Middleware | Status |
|---|---|---|---|
| `POST` | `/api/v1/storage/upload-slots` | `auth:sanctum`, `email.verified` | current |
| `POST` | `/api/v1/storage/media/{mediaId}/confirm` | `auth:sanctum`, `email.verified` | current |
| `GET` | `/api/v1/storage/media/{mediaId}/download-url` | `auth:sanctum`, `email.verified` | current |
| `DELETE` | `/api/v1/storage/media/{mediaId}` | `auth:sanctum`, `email.verified` | current |

Request responsibilities:

- `upload-slots` validates actor, storage purpose, MIME type, size, and owner context.
- `confirm` verifies the uploaded object exists before marking metadata confirmed.
- `download-url` authorizes access and returns a short-lived signed URL.
- `DELETE` marks media discarded and queues deletion work.

### Beauty mapping routes

Owner domain:

```text
App\Domains\Beauty
```

| Method | Path | Middleware | Status |
|---|---|---|---|
| `GET` | `/api/v1/beauty/product-mappings` | none | current |
| `POST` | `/api/v1/beauty/product-mappings` | `auth:sanctum`, `email.verified` | current |
| `PUT` | `/api/v1/beauty/product-mappings/{id}` | `auth:sanctum`, `email.verified` | current |

Authorization rule:

- writes are limited to super admins, shop owners, or shop staff for the mapped product's shop

### Beauty recommendation routes

Owner domain:

```text
App\Domains\Beauty
```

| Method | Path | Middleware | Status |
|---|---|---|---|
| `POST` | `/api/v1/beauty/recommendations/generate` | none, optional auth context | current |
| `GET` | `/api/v1/beauty/recommendations/{id}` | none, request-level authorization | current |

Authorization rule:

- anonymous recommendation generation may only use `session_id`
- authenticated users may only target their own `customer_id` and `profile_id`
- super admins may inspect any recommendation
- recommendation reads require either matching customer, matching profile, or matching session token

## Current request shapes

### `POST /api/v1/storage/upload-slots`

Required fields:

```json
{
  "purpose": "beauty_input",
  "asset_type": "source_photo",
  "owner_type": "profile",
  "owner_id": 12,
  "profile_id": 12,
  "shop_id": 3,
  "session_id": "guest-session-123",
  "file_name": "photo.jpg",
  "content_type": "image/jpeg",
  "size_bytes": 523412
}
```

Supported content types:

```text
image/jpeg
image/png
image/webp
image/heic
image/heif
application/pdf
```

Maximum size:

```text
10 MB
```

Response shape:

```json
{
  "data": {
    "media_id": 1,
    "storage_provider": "minio_aistor",
    "disk_name": "s3_beauty_inputs",
    "bucket": "nuvia-private-beauty-inputs",
    "object_key": "beauty/inputs/3/guest-session-123/1/source.jpg",
    "object_version": null,
    "upload_url": "https://...",
    "method": "PUT",
    "headers": {
      "Content-Type": "image/jpeg"
    },
    "expires_at": "2026-05-28T12:00:00+00:00"
  }
}
```

### `POST /api/v1/storage/media/{mediaId}/confirm`

Response shape:

```json
{
  "data": {
    "id": 1,
    "status": "confirmed"
  }
}
```

### `GET /api/v1/storage/media/{mediaId}/download-url`

Response shape:

```json
{
  "data": {
    "url": "https://...",
    "expires_at": "2026-05-28T13:00:00+00:00"
  }
}
```

### `POST /api/v1/beauty/product-mappings`

Request shape:

```json
{
  "product_id": 10,
  "concern_tags": ["dark-spot", "texture"],
  "skin_type_tags": ["oily", "combination"],
  "tone_tags": ["medium"],
  "undertone_tags": ["warm"],
  "ingredient_tags": ["niacinamide"],
  "avoid_tags": ["fragrance"],
  "explanation_template": "Supports brightening and texture-balancing needs."
}
```

### `POST /api/v1/beauty/recommendations/generate`

Request shape:

```json
{
  "session_id": "guest-session-123",
  "skin_type_tags": ["oily"],
  "tone_tags": ["medium"],
  "undertone_tags": ["neutral"],
  "concern_tags": ["dark-spot", "texture"],
  "ingredient_tags": ["niacinamide"],
  "avoid_tags": ["fragrance"],
  "limit": 4
}
```

Response shape:

```json
{
  "data": {
    "recommendations": [
      {
        "product_id": 1,
        "score": 87,
        "confidence": "high",
        "reasons": [
          "Matches oily skin profile",
          "Targets dark spot concern"
        ],
        "warnings": [
          "Avoid if sensitive to fragrance"
        ],
        "breakdown": {
          "skin_type_match": 1,
          "tone_match": 0.5,
          "undertone_match": 0.5,
          "concern_match": 1,
          "ingredient_match": 1,
          "product_tag_signal": 0.5,
          "avoid_penalty": 1
        },
        "product": {
          "id": 1,
          "name": "Example Product",
          "slug": "example-product"
        }
      }
    ]
  }
}
```

## Current security rules

- protected backend logic remains in Laravel only
- browser clients never receive storage credentials
- private media reads use signed URLs only
- recommendation writes do not trust arbitrary `customer_id` or `profile_id`
- product mapping writes are checked against product shop ownership

## Route maintenance rule

Update this file in the same commit when:

- a Phase 4 route is added or removed
- route middleware changes
- request or response shape changes
- authorization behavior changes
