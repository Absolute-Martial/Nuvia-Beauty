# Phase 4: Seller Consultation Foundation

## Status

```text
Planned after Phase 3 stabilization.
```

## Objective

Create the seller-assisted beauty consultation foundation that will later use Perfect Corp analysis results and existing recommendation logic.

## Product goal

Enable a vendor/seller to:

```text
start a consultation
attach private media through existing storage flow
capture structured session/profile data
view deterministic recommendations
save or discard the session
```

## Scope

In scope:

```text
beauty session model
beauty profile model
beauty profile snapshot model
AI task placeholder model
analysis result placeholder model
quota account/event foundation
session APIs
vendor consultation UI
save/discard flow
audit events where practical
```

Out of scope:

```text
live Perfect Corp calls
makeup virtual try-on
full customer self-scan
multi-provider orchestration
advanced analytics
```

## Backend target files

Create or update under:

```text
backend-engine/app/Domains/Beauty/
backend-engine/database/migrations/
backend-engine/routes/api/v1/beauty.php
```

## Proposed tables

```text
beauty_sessions
beauty_profiles
beauty_profile_snapshots
beauty_ai_tasks
beauty_analysis_results
beauty_quota_accounts
beauty_quota_events
audit_logs if not already available
```

## Session states

```text
draft
media_uploaded
analysis_pending
analysis_completed
saved
discarded
failed
```

## Proposed APIs

```http
POST /api/v1/beauty/sessions
GET  /api/v1/beauty/sessions/{id}
POST /api/v1/beauty/sessions/{id}/attach-media
POST /api/v1/beauty/sessions/{id}/save
POST /api/v1/beauty/sessions/{id}/discard
GET  /api/v1/beauty/sessions/{id}/recommendations
```

## Required behavior

```text
use existing beauty_media_assets table for uploaded media references
do not store raw images in MySQL
do not expose S3 credentials
create provider task placeholder only
recommendation can use existing deterministic scoring
save/discard must update session state
audit save/discard actions where practical
```

## Vendor portal UI

Add minimal vendor page or entry point:

```text
start consultation
select guest/new/returning customer if available
attach media
view recommendation results
save session
discard session
```

Keep UI simple. This is foundation, not final kiosk UX.

## Acceptance criteria

```text
Seller can create consultation session.
Seller can attach existing private media asset.
Session state updates correctly.
Seller can view recommendations for session criteria.
Seller can save or discard session.
No raw media is stored in database.
Docs and changelog are updated.
```

## Gate to Perfect Corp

Perfect Corp integration should not start until:

```text
consultation sessions exist
media can be attached
placeholder ai task/result models exist
recommendations can be generated from session/profile criteria
quota foundation exists
```
