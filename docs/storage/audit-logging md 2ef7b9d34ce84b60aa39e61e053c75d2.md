# audit-logging.md

Owner: Susank Shakya

<aside>
🧾

**`docs/storage/audit-logging.md`** · what storage access is logged, and what is never logged.

</aside>

# 1. Purpose & scope

Every sensitive storage operation is logged so access to private media is **fully traceable**. This page defines which events are captured, their schema, retention, where they surface, and the strict rules on what must never appear in a log.

# 2. Logged events

| Event | Captured fields |
| --- | --- |
| Signed URL minted | actor, object key, bucket, operation (PUT/GET), TTL, timestamp |
| Upload confirmed | actor, object key, size, content type |
| Result stored | source input key, result key, job id |
| Deletion | actor, object key, reason (expiry / consent withdrawal / manual) |
| Access denied | actor, attempted object key, policy that denied, timestamp |

# 3. Event schema

```json
{
  "event": "signed_url_minted",
  "actor": "user://36dd872b-594c-81a2-90a8-000243949ed5",
  "shop_id": "shop_123",
  "bucket": "nuvia-private-beauty-inputs",
  "object_key": "inputs/shop_123/sess_456/ab12.jpg",
  "operation": "PUT",
  "ttl_seconds": 900,
  "timestamp": "2026-05-30T14:00:00Z"
}
```

# 4. Principles

- Logs record **who** requested access, to **which** object, for **what** operation, and **when**.
- Logs **never** contain the media itself, full signed URLs, or storage credentials.
- Object keys (not bytes) are recorded; keys are tenant-scoped for traceability.
- Sensitive-action logging is a system-wide requirement (ADR 0012, zero-trust).

# 5. Retention & access

- Retained per the security sub-plan and surfaced in the **admin-panel audit views**.
- Logs are append-only within their retention window; deletions of media are themselves logged.
- Access to audit logs is role-restricted (operator/admin).

# 6. Monitoring & alerting

- Spikes in **access-denied** events or unusual mint volume can signal misconfiguration or abuse and should alert operators.
- Failed deletions (e.g. expiry sweep errors) are surfaced for follow-up so retention guarantees hold.
- Correlate storage events with consultation/session events for end-to-end traceability.

# 7. Related documentation

- Access rules: `private-bucket-policy.md`. Lifecycle: `media-lifecycle.md`. Architecture: `storage-architecture.md`.
- Broader audit & monitoring: `operations/` and `architecture/hld-system-architecture.md` (observability).