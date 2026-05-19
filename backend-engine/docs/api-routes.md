# Backend API Routes

Current API route documentation for `backend-engine` in the Nuvia Beauty `development` branch.

## Scope of this file

This file documents routes visible in the application-level route file:

```text
backend-engine/routes/api.php
```

Routes registered by installed packages, service providers, or the local `marvel/shop` package may exist outside this file. Document package-provided routes separately when they are audited.

## Current app-level API routes

### Authenticated user route

```http
GET /api/user
```

Current definition:

```php
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
```

Current behavior:

| Field | Value |
|---|---|
| Route file | `routes/api.php` |
| Middleware | `auth:api` |
| Response | Authenticated user object from `$request->user()` |
| Public route | No |

## Current route file imports

Current route file imports:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
```

## Current API route limitations

Current app-level `routes/api.php` is minimal. It does not yet define:

```text
/api/v1 routes
storage upload-slot routes
beauty media routes
provider orchestration routes
health routes
quota routes
admin operational routes
```

These may be added later, but they are not currently implemented in the app-level route file.

## Planned route convention

New backend-owned routes should use versioned API prefixes.

Recommended pattern:

```text
/api/v1/{domain}/{resource}
```

Examples for future implementation:

```text
/api/v1/storage/upload-slots
/api/v1/storage/media/{mediaId}/confirm
/api/v1/storage/media/{mediaId}/download-url
/api/v1/beauty/sessions
/api/v1/beauty/analysis/tasks/{taskId}/status
/api/v1/beauty/profiles/{profileId}
/api/v1/beauty/recommendations
/api/v1/admin/storage/health
```

Do not document planned route examples as current implemented routes.

## Route ownership rule

Route files should stay organized by domain as the backend grows.

Preferred future direction:

```text
routes/api.php                      # high-level includes or minimal core routes
routes/api/v1/storage.php           # storage/media routes
routes/api/v1/beauty.php            # beauty domain routes
routes/api/v1/admin.php             # admin operations routes
```

Alternative Laravel grouping inside `routes/api.php` is acceptable for small scopes, but domain route files are preferred once route count grows.

## Middleware rules

Protected routes must use existing authentication middleware. Do not trust frontend-provided user IDs, owner IDs, shop IDs, or role names.

Recommended access pattern:

```text
Auth middleware -> policy/gate/service authorization -> domain service action
```

Avoid:

```text
Request body owner_id -> direct database/storage action
```

## Response conventions

Recommended response envelope for new domain endpoints:

```json
{
  "data": {},
  "meta": {},
  "message": "Optional human-readable message"
}
```

Recommended error response shape:

```json
{
  "message": "Validation failed.",
  "errors": {
    "field": ["Reason"]
  }
}
```

Use Laravel defaults where appropriate, but keep new APIs predictable for frontend clients.

## Storage route plan

Not implemented yet.

Planned routes:

```http
POST /api/v1/storage/upload-slots
POST /api/v1/storage/media/{mediaId}/confirm
GET  /api/v1/storage/media/{mediaId}/download-url
DELETE /api/v1/storage/media/{mediaId}
```

Planned responsibilities:

| Route | Responsibility |
|---|---|
| `POST /upload-slots` | Validate actor/file intent and return short-lived presigned upload URL. |
| `POST /media/{mediaId}/confirm` | Verify uploaded object exists and mark media confirmed. |
| `GET /media/{mediaId}/download-url` | Authorize private read and return short-lived signed URL. |
| `DELETE /media/{mediaId}` | Mark media discarded and queue deletion. |

## Provider route plan

Not implemented yet.

Future provider routes should not expose provider credentials or raw provider payloads.

Correct flow:

```text
Frontend request -> backend domain endpoint -> backend job/service -> provider API -> normalized backend response
```

## Documentation update rule

When a route is added, changed, moved, deprecated, or removed, update this file in the same commit.

Required route documentation fields:

```text
HTTP method
Path
Middleware
Request shape
Response shape
Authorization rule
Owner service/domain
Current/planned/deprecated status
```
