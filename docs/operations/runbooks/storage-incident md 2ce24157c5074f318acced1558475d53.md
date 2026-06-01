# storage-incident.md

Owner: Susank Shakya

<aside>
🗄️

**`docs/operations/runbooks/storage-incident.md`** · respond to storage outages, signed-URL failures, or suspected bucket exposure.

</aside>

# 1. When to use

A storage outage, a spike in signed-URL failures, or a **suspected private-bucket exposure** (private media reachable without a valid signed URL).

# 2. Severity

- **Sev1** for any suspected exposure of private media; **Sev2** for availability-only issues.

# 3. Steps

1. **Contain first** — if exposure is suspected, confirm bucket isolation and block public access immediately.
2. Review access logs for unauthorized or denied access patterns.
3. **Rotate keys** if exposure is suspected (`S3_KEY`/`S3_SECRET`); invalidate outstanding signed URLs as needed.
4. Verify provider/endpoint health and `S3_USE_PATH_STYLE_ENDPOINT=true` configuration.
5. Capture evidence; notify and, for exposure, follow data-incident obligations.

# 4. Verification

- Private buckets (`nuvia-private-beauty-inputs/results/calibration`) are not publicly reachable.
- New signed URLs (PUT 15m / GET 60m) issue and expire correctly.

# 5. Escalation & communication

- Page the incident lead for any suspected exposure; engage the storage provider for outages.

# 6. Evidence & follow-up

- Record the timeline, scope, and remediation; run a post-incident review ([[incident-response.md](http://incident-response.md)](../incident-response%20md%20ad458a5261d74cd490e700293324e098.md)).

# 7. Related documentation

- Policy: `storage/private-bucket-policy.md`. Security tests: `testing/security-test-plan.md`. Decisions: [ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0012.