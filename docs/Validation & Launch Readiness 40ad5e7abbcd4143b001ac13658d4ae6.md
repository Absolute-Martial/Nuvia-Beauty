# Validation & Launch Readiness

Owner: Susank Shakya

<aside>
✅

This page defines validation and launch-readiness checks for Nuvia Beauty / MatchMuse Beauty. Use it before moving from stabilization into seller consultation, provider integration, or demo readiness.

</aside>

## Validation goals

- Confirm storage and media lifecycle are safe.
- Confirm recommendation behavior is explainable and deterministic.
- Confirm admin/vendor mapping workflows are usable.
- Confirm frontend surfaces do not expose provider or storage credentials.
- Confirm docs clearly separate current, planned, and out-of-scope behavior.
- Confirm demo scenarios are repeatable.

## Phase 03 validation checklist

### Backend

- [ ]  Storage disks/configuration validated.
- [ ]  Media metadata create/read/delete flows tested.
- [ ]  Recommendation API returns score, confidence, reasons, and warnings.
- [ ]  Error responses are clear and safe.
- [ ]  Audit events exist for sensitive flows.
- [ ]  No raw image bytes are stored in MySQL.

### Admin panel

- [ ]  Product mapping editor loads existing mappings.
- [ ]  Admin can update mapping attributes.
- [ ]  Missing/low-quality mappings are visible.
- [ ]  Storage/provider health status is visible or documented.

### Vendor portal

- [ ]  Vendor product edit flow supports mapping fields where allowed.
- [ ]  Vendor cannot access private provider/storage credentials.
- [ ]  Vendor flow labels current vs planned behavior.

### Storefront

- [ ]  Customer can see recommendations.
- [ ]  Recommendation explanations are readable.
- [ ]  Warnings are visible when relevant.
- [ ]  No clinical/medical claims are shown.
- [ ]  Scan-related UI is clearly marked if planned only.

### Documentation

- [ ]  README navigation matches actual folder map.
- [ ]  Phase index uses canonical names.
- [ ]  Roadmap lists current and planned phases.
- [ ]  Feature brainstorming is separate from implementation commitments.
- [ ]  Known gaps are documented.

## Demo readiness checklist

- [ ]  Demo customer profile prepared.
- [ ]  Demo product catalog seeded.
- [ ]  Demo mappings verified.
- [ ]  Demo recommendation scenario documented.
- [ ]  Seller consultation script prepared.
- [ ]  Failure/fallback scenario prepared.
- [ ]  Screenshots or evidence collected.
- [ ]  Deployment environment verified.

## Safety and privacy checklist

- [ ]  Perfect Corp keys are backend-only.
- [ ]  Storage credentials are backend-only.
- [ ]  Signed URL expiry is configured.
- [ ]  Media cleanup job exists or is documented.
- [ ]  Audit logging exists for scan/recommendation events.
- [ ]  Consent language is clear.
- [ ]  No medical diagnosis or treatment language appears in customer-facing copy.

## Acceptance criteria format

Use this format for every feature or phase:

```markdown
## Acceptance criteria
- Given [context], when [action], then [expected result].
- Given [failure case], when [action], then [safe fallback].
- Given [privacy-sensitive path], when [data is processed], then [credentials/raw media remain protected].
```

## Risk register starter

| Risk | Severity | Response |
| --- | --- | --- |
| Provider dependency blocks demo | High | Keep deterministic recommendation fallback available. |
| Docs describe planned behavior as current | Medium | Use Current / Planned / Not implemented labels. |
| Media lifecycle is incomplete | High | Validate cleanup, expiry, and audit flow before self-scan expansion. |
| Recommendations sound medical | High | Use safety review and controlled language templates. |
| Product mappings are too sparse | Medium | Add mapping completeness score and seed demo catalog. |