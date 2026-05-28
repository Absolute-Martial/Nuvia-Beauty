# Phase 8: Customer Self-Scan and Profile History

## Status

```text
Post-MVP. Start only after seller consultation, Perfect Corp P0, demo readiness, and personalized domain routing are stable.
```

## Objective

Add controlled customer-owned scan capability while keeping the existing seller-assisted flow as the primary validated retail path.

The goal is to let returning customers update their beauty profile from their own device, compare profile snapshots over time, and reuse recommendations without always returning to the shop.

## Scope

### In scope

```text
customer self-scan route
browser camera capture and upload-from-file fallback
quality gate before quota use
customer quota enforcement
profile snapshot timeline
latest-vs-previous profile comparison
customer consent and retention controls
saved recommendation regeneration
profile deletion/export request hooks
```

### Out of scope

```text
clinical diagnosis
medical treatment advice
full dermatology report
native mobile app
social sharing
cross-profile prediction
multi-provider routing
subscription billing
```

## Backend requirements

Add or extend backend support for customer-owned scan tasks.

```text
CustomerSelfScanController
CustomerScanPolicy
CustomerQuotaPolicy
ProfileSnapshotTimelineService
ProfileSnapshotComparisonService
CustomerMediaRetentionService
ProfileDeleteRequestService if missing
```

Required behavior:

```text
customer can create a self-scan session only for own profile
customer scan uses customer quota, not shop quota
quality gate must pass before provider task is created
raw input media remains private and expires by default
analysis result writes beauty_analysis_results first
profile snapshot is appended, never overwritten
recommendations regenerate from latest snapshot
```

## API requirements

Suggested endpoints:

```http
POST /api/v1/beauty/customer/scans
POST /api/v1/beauty/customer/scans/{id}/attach-media
POST /api/v1/beauty/customer/scans/{id}/analysis/start
GET  /api/v1/beauty/customer/scans/{id}/status
GET  /api/v1/beauty/customer/profile/snapshots
GET  /api/v1/beauty/customer/profile/snapshots/{id}
GET  /api/v1/beauty/customer/profile/compare?from={snapshotId}&to={snapshotId}
POST /api/v1/beauty/customer/profile/delete-request
```

Public frontend must not call Perfect Corp directly.

## Frontend requirements

Customer PWA screens:

```text
self-scan landing
camera permission screen
guided capture screen
quality feedback screen
upload fallback screen
analysis progress screen
latest profile snapshot
snapshot history timeline
recommendation refresh screen
profile privacy controls
```

UX rules:

```text
show quota before scan starts
show why scan was rejected before quota is consumed
show demo label when demo-mode data is used
show latest provider/model version on profile details
use retail-safe wording only
```

## Data model notes

Do not collapse tables.

```text
beauty_sessions = customer scan session wrapper
beauty_media_assets = private input/result pointers
beauty_ai_tasks = provider lifecycle and cost
beauty_analysis_results = raw/normalized provider output
beauty_profile_snapshots = reusable profile state
beauty_recommendations = regenerated recommendations
beauty_quota_events = quota ledger
```

## Validation checklist

```text
[ ] customer can start a self-scan session
[ ] camera permission denial has upload fallback
[ ] failed quality gate does not consume quota
[ ] successful scan consumes quota server-side
[ ] raw media is private
[ ] analysis result inserts before snapshot
[ ] latest snapshot becomes active
[ ] older snapshots remain available
[ ] comparison view uses retail-safe wording
[ ] recommendations regenerate from latest snapshot
[ ] no Perfect Corp keys are visible in frontend
```

## Exit criteria

```text
A logged-in customer can run a controlled self-scan, receive a new profile snapshot, compare it with previous snapshots, and receive refreshed recommendations without exposing raw media or provider credentials.
```

## Implementation prompt

```text
Implement Phase 8: Customer Self-Scan and Profile History.

Rules:
- Do not replace seller-assisted consultation.
- Do not expose provider or storage credentials.
- Do not store raw images in MySQL.
- Do not make clinical claims.
- Do not overwrite old profile snapshots.

Backend:
1. Add customer self-scan APIs.
2. Enforce ownership and quota.
3. Reuse existing media storage and Perfect Corp P0 orchestration.
4. Append profile snapshots.
5. Add snapshot timeline and comparison APIs.
6. Add retention/delete request hooks.

Frontend:
1. Add customer scan route.
2. Add camera/upload flow.
3. Add quality gate feedback.
4. Add progress/status UI.
5. Add profile history and comparison UI.
6. Add recommendation refresh UI.

Docs:
1. Update customer workflow docs.
2. Update API docs.
3. Update data-flow docs.
4. Update changelog.

Return:
- files changed
- migrations added
- routes added
- test commands
- security notes
- known gaps
```
