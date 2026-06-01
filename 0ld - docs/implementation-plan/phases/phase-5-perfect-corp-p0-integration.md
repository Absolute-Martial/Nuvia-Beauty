# Phase 5: Perfect Corp P0 Integration

## Status

```text
Planned after Phase 3 stabilization and Phase 4 seller consultation foundation.
```

## Objective

Integrate one Perfect Corp API through the Laravel backend to satisfy the Perfect Corp requirement and create a retail/customer-value demo flow.

## Perfect Corp challenge alignment

Requirement:

```text
Integrate at least one Perfect Corp API.
Demonstrate clear consumer or retail value.
Build an immersive web or mobile experience using Perfect Corp AI/AR technology.
```

Preferred P0 API:

```text
AI Skin Analysis
```

Fallback P0 API:

```text
Skin Tone / Color Tone Analysis
```

Deferred APIs:

```text
Makeup virtual try-on
fashion visualization
gen AI text-to-image
multi-API chaining
```

## Scope

In scope:

```text
backend-only Perfect Corp client
provider environment variables
demo mode fallback
create provider task job
poll provider task job
normalize provider result
store analysis result
create profile snapshot
regenerate recommendations
vendor consultation status UI
```

Out of scope:

```text
frontend direct Perfect Corp calls
Makeup VTO as first API
full customer self-scan
clinical diagnosis language
multi-provider abstraction
```

## Environment variables

Add backend-only variables:

```env
PERFECT_CORP_API_BASE_URL=
PERFECT_CORP_API_KEY=
PERFECT_CORP_API_BEARER_KEY=
PERFECT_CORP_ENABLED=false
PERFECT_CORP_DEMO_MODE=true
PERFECT_CORP_TIMEOUT_SECONDS=60
PERFECT_CORP_POLL_INTERVAL_SECONDS=2
PERFECT_CORP_MAX_ATTEMPTS=60
```

## Backend target files

Create/update:

```text
backend-engine/app/Domains/Beauty/Services/PerfectCorpClient.php
backend-engine/app/Domains/Beauty/Services/PerfectCorpTaskService.php
backend-engine/app/Domains/Beauty/Services/NormalizePerfectCorpResultService.php
backend-engine/app/Domains/Beauty/Jobs/CreatePerfectCorpAnalysisTask.php
backend-engine/app/Domains/Beauty/Jobs/PollPerfectCorpAnalysisTask.php
backend-engine/config/services.php
backend-engine/.env.example
backend-engine/routes/api/v1/beauty.php
```

## Proposed APIs

```http
POST /api/v1/beauty/sessions/{id}/analysis/start
GET  /api/v1/beauty/analysis/{taskId}/status
```

## Required flow

```text
seller starts consultation
seller attaches private media
backend validates session, media, and quota
backend creates beauty_ai_tasks row
queue job calls Perfect Corp or demo provider
polling job receives result
backend stores beauty_analysis_results
backend creates beauty_profile_snapshots
backend regenerates recommendations
vendor UI displays normalized result and recommendations
```

## Demo mode behavior

Demo mode must:

```text
not call Perfect Corp
not consume provider quota
use seeded/sample normalized analysis result
exercise the same database/status/recommendation flow
be controlled by PERFECT_CORP_DEMO_MODE=true
```

## Security rules

```text
Perfect Corp keys stay backend-only.
Frontend never receives provider keys.
Frontend never calls Perfect Corp directly.
Raw provider payload is backend-only.
Frontend receives normalized result only.
No full signed URLs in logs.
No raw media in MySQL.
No medical diagnosis claims.
```

## Acceptance criteria

```text
analysis start endpoint creates a task
status endpoint returns task state
demo mode completes without provider call
analysis result is stored
profile snapshot is created
recommendations regenerate from result
vendor UI shows analysis status and recommendations
no Perfect Corp keys appear in frontend bundle/network
```

## Live-mode gate

Live Perfect Corp calls may be enabled only after:

```text
Perfect Corp credentials are available
provider docs/API flow are confirmed
queue worker runs successfully
demo mode works end-to-end
storage/private media flow is verified
security review passes
```

## Contact

Perfect Corp contact:

```text
valerie_torres@perfectcorp.com
```
