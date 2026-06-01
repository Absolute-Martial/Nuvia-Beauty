# storage-test-plan.md

Owner: Susank Shakya

<aside>
🗄️

**`docs/testing/storage-test-plan.md`** · verifying buckets, signed URLs, lifecycle, and access control.

</aside>

# 1. Purpose & scope

This plan covers the MinIO/AIStor S3-compatible storage layer: buckets, pre-signed URLs, lifecycle/retention, and access isolation. Storage holds private beauty media, so these tests are a core safety guarantee.

# 2. What we test

| Area | Checks |
| --- | --- |
| Signed uploads | Short-TTL (15 min) PUT URLs work; expired URLs rejected. |
| Signed downloads | TTL (60 min) GET URLs work; expired/forged URLs rejected. |
| Isolation | Private buckets (`nuvia-private-beauty-inputs/results/calibration`) never publicly reachable; only `nuvia-public-assets` is public. |
| Provider config | `S3_USE_PATH_STYLE_ENDPOINT=true`, `S3_PROVIDER=minio_aistor`. |
| Lifecycle | Retention/expiry honored; consent withdrawal removes inputs. |

# 3. Commands

```
php artisan test --filter=Storage
```

# 4. Coverage & gates

- Every private bucket has a negative public-access test.
- Signed-URL expiry is tested for both PUT and GET.
- Consent-withdrawal cleanup is verified end to end.

# 5. Evidence

- Store results under `implementation-plan/evidence/storage-tests/`.

# 6. Limitations & future enhancements

- Cross-region replication / DR testing is tracked under operations backup/restore.

# 7. Related documentation

- Storage: `storage/storage-architecture.md`, `storage/private-bucket-policy.md`, `storage/media-lifecycle.md`. Security: [[security-test-plan.md](http://security-test-plan.md)](security-test-plan%20md%20a33afc60e2e241aba82fd689e96659b4.md).