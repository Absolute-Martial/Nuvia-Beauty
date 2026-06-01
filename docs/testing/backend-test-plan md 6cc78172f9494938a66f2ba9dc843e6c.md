# backend-test-plan.md

Owner: Susank Shakya

<aside>
⚙️

**`docs/testing/backend-test-plan.md`** · verifying the Laravel backend — domains, APIs, jobs, validation, and settings.

</aside>

# 1. Purpose & scope

This plan covers `backend-engine` (Laravel ^13.0 / PHP ^8.3): domain logic, API contracts, queue jobs, validation, and the settings system. Because the backend is the only trusted tier, its tests are the primary guarantee of correctness and safety. All provider work is exercised through demo-mode stubs.

# 2. What we test

| Area | Focus |
| --- | --- |
| Domain logic | Storage + Beauty domains; recommendation scoring output shape `{product_id, score, confidence, reasons[], warnings[]}`. |
| API contracts | Form Request validation, response shape, status codes, Policy authorization. |
| Queue jobs | Analysis pipeline, idempotency, retries, fallback to demo-mode, dead-letter handling. |
| Settings system | Reads/writes, resolution order, effect on behavior. |
| Consent & quota | Consent gates; quota debit/reconcile with no double-charge. |
| Demo-mode | Deterministic stubbed provider results. |

# 3. Tooling

- PHPUnit / Pest for unit + feature tests.
- Larastan / PHPStan at **max**, `declare(strict_types=1)` enforced in CI (see `backend-engine/validation.md`).

# 4. Commands

```
php artisan test
vendor/bin/phpstan analyse
```

# 5. Coverage & gates

- Every endpoint has Form Request + Policy tests (positive and negative).
- Every queue job has an idempotency + failure-path test.
- PHPStan max passes with no baseline regressions.

# 6. Evidence

- Store output under `implementation-plan/evidence/backend-tests/`.

# 7. Limitations & future enhancements

- Performance/load testing is tracked separately under operations; this plan focuses on correctness and safety.

# 8. Related documentation

- API: `backend-engine/api-contracts.md`. Validation: `backend-engine/validation.md`. Jobs: `backend-engine/queue-jobs.md`. Security suite: [[security-test-plan.md](http://security-test-plan.md)](security-test-plan%20md%20a33afc60e2e241aba82fd689e96659b4.md).