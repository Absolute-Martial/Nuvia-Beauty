# Backend Data Flow and Implementation

Current and planned data flow documentation for `backend-engine` in the Nuvia Beauty `development` branch.

## Scope

This document explains how data should move through the backend service.

It separates:

```text
Current behavior
Planned behavior
Not implemented yet
```

## Service boundary

The backend is the protected integration boundary for the platform.

Correct boundary:

```text
Frontend apps -> backend-engine -> MySQL / Redis / storage / external providers
```

Incorrect boundary:

```text
Frontend apps -> MySQL
Frontend apps -> Redis
Frontend apps -> S3/MinIO/AIStor credentials
Frontend apps -> provider API keys
```

## Current request flow

Current app-level route flow:

```text
HTTP request
  -> Laravel router
  -> middleware
  -> route closure/controller/package route
  -> response
```

Current visible app-level route:

```text
GET /api/user -> auth:api -> request user response
```

Additional route behavior may be registered by packages or service providers.

## Recommended request implementation flow

For new backend-owned domain routes, use this pattern:

```text
HTTP request
  -> route
  -> middleware
  -> form request / validation
  -> policy or authorization service
  -> domain service
  -> model/repository/storage/provider layer
  -> resource/DTO response
```

Controllers should coordinate only. They should not directly contain storage, provider, quota, or complex database workflows.

## Database data flow

Current database service:

```text
MySQL 8.0
```

Current backend connection variables in Compose:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=${DB_DATABASE:-nuvia_beauty}
DB_USERNAME=${DB_USERNAME:-nuvia_beauty}
DB_PASSWORD=${DB_PASSWORD:-nuvia_beauty}
```

Correct database flow:

```text
Backend request/service -> Laravel model/query/migration layer -> MySQL
```

Frontend apps must not connect to MySQL.

## Cache/session/queue data flow

Current Redis service:

```text
Redis 7.4 Alpine
```

Compose backend environment uses Redis for:

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

`.env.example` local defaults are different:

```env
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
```

This means async processing depends on the active environment.

Correct queue flow for future jobs:

```text
API request
  -> validate/auth/check quota
  -> create DB record
  -> dispatch job
  -> Redis queue
  -> worker processes job
  -> update DB status
  -> frontend polls or receives status
```

## Current storage flow

Current Laravel filesystem disks:

```text
local
public
s3
```

Current default disk:

```env
FILESYSTEM_DISK=local
```

Current direct storage status:

- Local disk is the default in `.env.example`.
- A public-oriented `s3` disk exists.
- The S3 Flysystem adapter dependency exists.
- Dedicated public/private S3-compatible disks are not yet implemented.
- Upload slot endpoints are not yet implemented.

## Planned S3-compatible upload flow

Not implemented yet.

Target flow:

```text
Frontend selects file
  -> POST /api/v1/storage/upload-slots
  -> backend validates auth, purpose, MIME type, size, owner/session
  -> backend creates pending media metadata
  -> backend returns short-lived presigned PUT URL
  -> browser PUTs directly to S3-compatible storage
  -> frontend POSTs confirm endpoint
  -> backend HEADs object and verifies metadata
  -> backend marks media confirmed
```

Backend responsibilities:

```text
select disk/bucket
create object key
validate file intent
create media metadata
issue presigned URL
confirm object exists
authorize future reads
queue deletion when needed
```

Frontend responsibilities:

```text
request upload slot
upload to returned URL
confirm upload
never store credentials
never assume private object is permanently accessible
```

## Planned private download flow

Not implemented yet.

Target flow:

```text
Frontend requests media access
  -> backend authenticates user
  -> backend checks ownership/role/policy
  -> backend creates short-lived signed URL
  -> backend logs access event without logging signed URL
  -> frontend uses signed URL temporarily
```

Private media must not be served through permanent public URLs.

## Planned provider integration flow

Provider variables already exist in `.env.example`:

```env
YOUCAM_API_BASE_URL=https://yce-api-01.makeupar.com
YOUCAM_API_KEY=
YOUCAM_API_BEARER_KEY=
```

Provider keys are backend-only.

Correct provider flow:

```text
Frontend action
  -> backend route
  -> backend validates quota/storage/session
  -> backend job/service calls provider
  -> provider result returned to backend
  -> backend normalizes result
  -> backend stores normalized result
  -> frontend reads backend result endpoint
```

Incorrect provider flow:

```text
Frontend calls provider API directly with provider key
```

## Error flow

Recommended backend error handling for new endpoints:

```text
Validation error -> HTTP 422
Unauthorized -> HTTP 401
Forbidden -> HTTP 403
Not found -> HTTP 404
Quota or billing-style limit -> HTTP 402 or HTTP 429 depending on feature
Provider unavailable -> HTTP 502/503
Provider timeout -> HTTP 504
Unhandled server error -> HTTP 500
```

Use Laravel validation responses where possible.

## Audit/logging flow

Backend logs must not contain:

```text
Raw API keys
S3 access keys
MinIO/AIStor root credentials
Full private signed URLs
Raw image bytes
Full provider raw payloads when sensitive
```

Safe logging examples:

```text
media_id
user_id
shop_id
provider task id
object bucket/key hash or structured reference
status transitions
error codes
```

## Implementation layering

Preferred backend layering for new modules:

```text
app/Domains/{Domain}/
├── Controllers/
├── Requests/
├── Services/
├── Jobs/
├── DTO/
├── Models/
├── Policies/
└── Support/
```

Controllers:

```text
HTTP coordination only
request validation handoff
response formatting
```

Services:

```text
business rules
storage orchestration
provider orchestration
quota rules
database transactions
```

Jobs:

```text
slow external operations
retryable provider calls
delete/expiry operations
async status transitions
```

Policies:

```text
ownership checks
role checks
shop/customer/vendor access checks
```

DTOs/resources:

```text
stable request/response shapes
provider normalization
frontend-safe payloads
```

## Transaction rule

Use database transactions where multiple records must change together.

Examples:

```text
create media record + reserve upload slot metadata
create task + deduct quota event
store provider result + update task status
mark media discarded + queue deletion record
```

Avoid holding a DB transaction open during external provider calls or object storage uploads.

## Current known gaps

```text
No dedicated domain module for storage yet.
No upload-slot API yet.
No media metadata table yet.
No explicit queue worker Compose service yet.
No documented package route inventory yet.
No S3-compatible private disk separation yet.
```

## Update rule

Update this file when any of these change:

```text
route flow
storage flow
provider flow
queue/job flow
database ownership
error handling convention
logging/security boundary
new domain module layout
```
