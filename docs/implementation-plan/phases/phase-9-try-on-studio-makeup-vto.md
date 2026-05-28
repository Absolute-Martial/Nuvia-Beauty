# Phase 9: Try-On Studio and Makeup VTO

## Status

```text
Post-P0 / P1. Start only after Perfect Corp analysis flow is stable and recommendations are already explainable.
```

## Objective

Add a controlled makeup virtual try-on layer that improves visual purchase confidence without weakening the core profile/recommendation system.

This phase turns recommendation cards into optional visual try-on sessions for supported makeup products.

## Scope

### In scope

```text
makeup VTO provider task type
supported makeup product taxonomy
try-on eligible product mapping
before/after comparison UI
try-on result media storage
session-level result gallery
quota/cost tracking for VTO
save/discard try-on results
```

### Out of scope

```text
multi-API transformation chains
hair try-on
jewelry try-on
clothing try-on
public social sharing
native mobile app
unlimited free try-on
```

## Backend requirements

Add or extend:

```text
MakeupVtoTaskService
CreateMakeupVtoTask job
PollMakeupVtoTask job
NormalizeMakeupVtoResult service
TryOnEligibilityService
TryOnResultStorageService
```

Data requirements:

```text
beauty_ai_tasks.task_type = makeup_vto
beauty_analysis_results.analysis_type = makeup_vto or beauty_try_on_results table if separation is needed
beauty_media_assets.asset_type = try_on_result
beauty_product_mappings.try_on_supported = true/false
beauty_product_mappings.provider_sku_ref or equivalent mapping field
```

Rules:

```text
use only products marked try-on eligible
use existing private media assets
store result images privately
return signed result URLs only after authorization
track quota/cost separately from analysis
allow demo mode with seeded try-on result
```

## API requirements

Suggested endpoints:

```http
POST /api/v1/beauty/sessions/{id}/try-on/start
GET  /api/v1/beauty/try-on/{taskId}/status
GET  /api/v1/beauty/sessions/{id}/try-on-results
POST /api/v1/beauty/try-on-results/{id}/save
POST /api/v1/beauty/try-on-results/{id}/discard
```

## Frontend requirements

Customer/seller UI:

```text
Try-On button on eligible recommendation cards
before/after slider
result gallery
product shade selector if supported
processing state
quota/cost notice
save/discard controls
fallback demo result state
```

Vendor/admin UI:

```text
mark product as try-on eligible
map provider SKU/shade/reference
view unsupported product warnings
```

## Safety and quality rules

```text
Do not imply the virtual result is guaranteed to match real-world application.
Label try-on as simulated visual preview.
Do not use beautified image as new skin-analysis input.
Do not run VTO before P0 analysis/recommendation flow is stable.
Do not create multi-API chaining in this phase.
```

## Validation checklist

```text
[ ] only try-on eligible products show Try-On button
[ ] unsupported products show no provider action
[ ] VTO task creates beauty_ai_tasks row
[ ] result media stores privately
[ ] before/after slider works
[ ] save/discard result works
[ ] quota/cost event is recorded
[ ] demo-mode try-on works without live provider
[ ] no provider keys visible in frontend
```

## Exit criteria

```text
A seller or customer can select a recommended makeup product, run a controlled try-on preview, compare before/after, and save or discard the result while private media and provider credentials remain protected.
```

## Implementation prompt

```text
Implement Phase 9: Try-On Studio and Makeup VTO.

Rules:
- Do not start this unless Perfect Corp P0 analysis is stable.
- Do not add hair, jewelry, or clothing VTO.
- Do not add multi-API chaining.
- Do not expose provider keys.
- Do not store raw images in MySQL.
- Do not claim visual preview is exact.

Backend:
1. Add makeup_vto task type.
2. Add provider service/jobs for makeup try-on.
3. Add try-on eligibility service.
4. Store result media privately.
5. Add save/discard endpoints.
6. Add demo-mode seeded try-on result.

Frontend:
1. Add Try-On action to eligible recommendation cards.
2. Add before/after comparison UI.
3. Add processing/status UI.
4. Add save/discard result controls.
5. Add vendor/admin eligibility mapping if needed.

Docs:
1. Update API docs.
2. Update database docs.
3. Update customer/vendor workflow docs.
4. Update changelog.

Return:
- files changed
- routes added
- migrations added
- provider assumptions
- test commands
- security notes
- known gaps
```
