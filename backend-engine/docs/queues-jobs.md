# Backend Queues and Jobs

Queue and job documentation for `backend-engine` in the Nuvia Beauty `development` branch.

## Current queue state

Queue behavior depends on environment.

Current `.env.example` default:

```env
QUEUE_CONNECTION=sync
```

Current Docker Compose backend default:

```env
QUEUE_CONNECTION=${QUEUE_CONNECTION:-redis}
```

Current Redis service:

```text
service: redis
image: redis:7.4-alpine
port: 6379
volume: redis_data:/data
```

## Current cache/session state

Current `.env.example` local defaults:

```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

Current Docker Compose backend defaults:

```env
CACHE_DRIVER=${CACHE_DRIVER:-redis}
SESSION_DRIVER=${SESSION_DRIVER:-redis}
REDIS_HOST=redis
REDIS_PORT=6379
```

## Current worker service status

Current `docker-compose.yml` does not define a dedicated queue worker service.

Current status:

```text
Redis is available.
Backend can be configured to use Redis queues.
Dedicated queue worker container is not implemented yet.
```

If `QUEUE_CONNECTION=redis` is used without a worker process, queued jobs may not be processed unless another process runs `php artisan queue:work`.

## Current sync queue behavior

With:

```env
QUEUE_CONNECTION=sync
```

Jobs run immediately in the request lifecycle.

This is useful for simple local development but wrong for slow operations such as:

```text
external provider calls
image processing
large storage operations
media deletion batches
provider polling
email or notification fan-out
```

## Planned worker service

Not implemented yet.

Recommended Compose service:

```yaml
backend-worker:
  image: ${NUVIA_BACKEND_IMAGE:-nuvia-beauty-backend:local}
  restart: unless-stopped
  depends_on:
    - db
    - redis
  environment:
    <<: *backend_env
  command: php artisan queue:work redis --sleep=3 --tries=3 --timeout=120
  networks:
    - nuvia_beauty_net
```

## Planned job categories

Not implemented yet.

Recommended future jobs:

| Job | Purpose |
|---|---|
| `DeleteExpiredMediaAssets` | Delete expired private objects and update media status. |
| `DiscardMediaAsset` | Delete or mark discarded user/session media. |
| `CreateProviderTask` | Prepare uploaded media and create provider-side task. |
| `PollProviderTask` | Poll provider task status and persist normalized result. |
| `ReconcileQuotaUsage` | Adjust estimated vs actual units after task completion. |
| `SyncStorageLifecycleRules` | Create/update object lifecycle rules in S3-compatible storage. |
| `SendTransactionalNotification` | Send email/SMS/notification events outside request lifecycle. |

## Job design rules

Jobs should be:

```text
idempotent
retry-safe
small in responsibility
observable through logs/status fields
safe to run after partial failure
```

Avoid jobs that:

```text
require browser state
hold database transactions during external calls
log secrets or signed URLs
perform multiple unrelated workflows
silently swallow provider/storage failures
```

## Retry rules

Recommended retry behavior for external calls:

```text
max attempts: 3
backoff: exponential with jitter
timeout: explicit per job
failure state: persisted in database
```

Recommended failure data:

```text
job class
domain record id
attempt count
error code
safe error message
failed_at timestamp
```

Do not store raw secrets, full signed URLs, or raw sensitive provider payloads in failure logs.

## Provider job flow

Not implemented yet.

Recommended flow:

```text
API request
  -> validate auth/quota/storage
  -> create task record
  -> dispatch CreateProviderTask
  -> provider task created
  -> dispatch PollProviderTask or schedule polling
  -> provider result ready
  -> normalize result
  -> store normalized result
  -> update task status
  -> frontend reads status/result endpoint
```

## Media deletion job flow

Not implemented yet.

Recommended flow:

```text
scheduled command or API discard action
  -> find expired/discarded media records
  -> dispatch delete job
  -> delete object from storage
  -> mark record deleted
  -> record failure if deletion fails
```

## Queue naming direction

Recommended queue names:

```text
default
media
provider
notifications
maintenance
```

Example:

```php
DeleteExpiredMediaAssets::dispatch()->onQueue('media');
```

## Scheduling direction

Not implemented yet.

Recommended scheduled tasks:

```text
Delete expired media assets: hourly or daily
Retry eligible failed provider tasks: every few minutes
Quota reset/reconciliation: daily/monthly depending plan
Storage lifecycle sync: on deploy or daily
```

## Observability requirements

For future jobs, persist status in domain tables where user-visible or operationally important.

Examples:

```text
media status
provider task status
quota event status
failed deletion status
```

Logs alone are not enough for user-facing or admin-facing workflows.

## Update rule

Update this file when:

```text
QUEUE_CONNECTION defaults change
Redis configuration changes
worker service is added to Compose
new jobs are added
job retry/backoff rules change
scheduled commands are added
queue names change
```
