# private-bucket-policy.md

Owner: Susank Shakya

<aside>
🔐

**`docs/storage/private-bucket-policy.md`** · access rules and enforcement layers that keep beauty media private.

</aside>

# 1. Purpose & scope

This page defines the **access-control policy** for the private buckets that hold beauty inputs, results, and calibration data, and the layers that enforce it. The goal is absolute: no anonymous, public, or client-credentialed access to private media — ever.

# 2. Policy

- Private buckets (`nuvia-private-beauty-inputs`, `nuvia-private-beauty-results`, `nuvia-private-calibration`) **deny all anonymous and public access**.
- Only `backend-engine` holds storage credentials. It mints **scoped, short-lived** signed URLs per object and operation.
- Signed URLs: PUT TTL **15 min**, GET TTL **60 min**. No long-lived, wildcard, or bucket-level URLs.
- **Path-style endpoints only** (`S3_USE_PATH_STYLE_ENDPOINT=true`).
- **No cross-bucket reads** — results are written to the results bucket, never the inputs bucket.
- Keys are tenant-scoped (`{shop_id}/{session_id}/...`); a URL is only ever minted for an object the requester is authorized to access.

# 3. Enforcement layers

Access is defended in depth, so a failure in any single layer does not expose media.

| Layer | Control |
| --- | --- |
| Storage (bucket policy + IAM) | Public access blocked; credentials scoped to the backend service identity only. |
| Backend authorization | Laravel policies gate who may request a URL for a given object (owner / shop / role). |
| Signed-URL scope | Each URL is bound to one object, one operation, and a short TTL. |
| Tenancy | `shop_id`-scoped keys plus server-side shop resolution prevent cross-tenant access (ADR 0010). |
| Audit | Every mint/access/deletion is logged for traceability (`audit-logging.md`). |

# 4. Example bucket policy (deny public)

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "DenyPublicAccess",
      "Effect": "Deny",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::nuvia-private-beauty-inputs/*",
      "Condition": { "Null": { "aws:userid": "true" } }
    }
  ]
}
```

The backend service identity holds a separate, least-privilege grant allowing only the object operations it needs (PUT/GET/DELETE on the scoped prefixes).

# 5. Threat model & mitigations

| Threat | Mitigation |
| --- | --- |
| Leaked signed URL | Short TTL (15m/60m) limits the window; URL is single-object, single-operation. |
| Credential exposure in frontend | No storage credentials ever reach the client; backend-only minting. |
| Cross-tenant access | Tenant-scoped keys + server-side shop resolution + backend policies. |
| Public misconfiguration | Explicit deny-public bucket policy; tested in the storage test plan. |

# 6. Related documentation

- Architecture: `storage-architecture.md`. Lifecycle: `media-lifecycle.md`. Auditing: `audit-logging.md`.
- Decisions: [ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) (ADR 0003, 0009, 0010, 0012). Testing: `testing/`.