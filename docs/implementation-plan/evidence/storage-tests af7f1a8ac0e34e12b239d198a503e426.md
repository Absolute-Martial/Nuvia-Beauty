# storage-tests/

Owner: Susank Shakya

<aside>
📁

**`docs/implementation-plan/evidence/storage-tests/`** — evidence from storage test runs (signed URLs, isolation, lifecycle).

</aside>

## 2026-06-01 — Phase 3 storage validation

Environment: local Docker Compose S3-compatible endpoint at `http://aistor:9000`, backed by the dev MinIO-compatible service.

Command and result:

- `php artisan test --filter=StorageLifecycleTest`: passed, 2 tests and 18 assertions.

Validated behavior:

- Upload slot creation produced a short-lived presigned private upload URL.
- Confirmed object existence before marking media confirmed.
- Unsigned private object reads were rejected.
- Signed download worked after authorization.
- Expired signed upload and download URLs were rejected.
- Delete/discard path updated metadata and removed storage objects where practical.
