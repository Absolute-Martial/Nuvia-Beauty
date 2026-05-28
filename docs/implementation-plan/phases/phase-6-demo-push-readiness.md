# Phase 6: Demo and Push Readiness

## Status

```text
Planned after stabilization, seller consultation foundation, and Perfect Corp P0 demo-mode integration.
```

## Objective

Prepare Nuvia Beauty for a credible product/demo push showing beauty-commerce differentiation, private media handling, seller consultation, Perfect Corp value, and explainable recommendations.

## Target demo flow

```text
seller opens vendor consultation screen
seller starts guest consultation
seller uploads or attaches customer image
backend stores media through private S3-compatible storage
seller runs analysis in demo or live provider mode
system shows processing state
backend stores normalized result
profile snapshot appears
recommendation cards appear with scores, reasons, and warnings
seller saves or discards session
```

## Required demo data

Minimum:

```text
10 beauty products
10 beauty_product_mappings
3 strong recommendation examples
1 avoid-tag warning example
1 seller consultation session
1 demo provider analysis result
```

Sample tags:

```text
skin types: oily, dry, combination, sensitive
concerns: acne, dark_spots, hydration, dullness
tones: fair, medium, deep
undertones: warm, cool, neutral
ingredients: niacinamide, hyaluronic_acid, salicylic_acid
avoid tags: fragrance, alcohol, essential_oil
```

## Validation checklist

Service checks:

```text
[ ] storefront reachable
[ ] admin-panel reachable
[ ] vendor-portal reachable
[ ] backend API reachable
[ ] S3-compatible API reachable
[ ] storage console access reviewed
```

Backend checks:

```text
[ ] migrations run
[ ] php artisan route:list --path=api/v1 works
[ ] php artisan list | grep beauty works
[ ] upload-slot endpoint works
[ ] confirm upload endpoint works
[ ] signed download endpoint works
[ ] recommendation endpoint works
[ ] event ingestion works
[ ] recompute command works
[ ] consultation session endpoints work
[ ] provider demo-mode flow works
```

Storage checks:

```text
[ ] public bucket serves public object
[ ] private bucket blocks direct public read
[ ] signed PUT works
[ ] backend confirm verifies object
[ ] signed GET works
```

Frontend checks:

```text
[ ] storefront renders recommendation panel
[ ] recommendation reasons render
[ ] recommendation warnings render
[ ] admin mapping editor works
[ ] vendor mapping editor works
[ ] vendor consultation UI works
[ ] analysis status UI works
```

Security checks:

```text
[ ] no S3/MinIO/AIStor keys in frontend
[ ] no Perfect Corp keys in frontend
[ ] private media requires authorization
[ ] no medical diagnosis wording
```

## Deployment checklist

```text
[ ] development branch pushed
[ ] review completed before production
[ ] backend image built
[ ] migrations executed
[ ] frontend images built
[ ] env variables configured
[ ] storage buckets configured
[ ] queue worker available if provider jobs are enabled
[ ] smoke tests completed
[ ] changelog updated
```

## Success criteria

```text
Demo can be completed without manual database edits.
Recommendation output is explainable.
Private media remains private.
Provider API or demo-mode integration is visible in the flow.
Admin/vendor mapping workflows support the demo.
Docs explain current vs planned scope.
```

## Known gaps to document before push

```text
whether provider live mode is enabled or demo-only
whether customer profile reopen is implemented or planned
whether vendor mapping requires admin review
whether quota is enforced or placeholder-only
whether queue worker is deployed or command-based
```

## Post-demo next candidates

```text
live Perfect Corp enablement
Makeup VTO as P1
customer profile reopen flow
admin failed task dashboard
quota management UI
mapping quality dashboard
provider retry handling
```
