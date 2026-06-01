# staging-deployment.md

Owner: Susank Shakya

<aside>
📦

**`docs/deployment/staging-deployment.md`** · deploy the stack to staging and verify it before any production consideration.

</aside>

# 1. Purpose & scope

This page describes how to **deploy to staging and verify** the stack end to end. Staging mirrors production configuration; the provider runs in demo-mode unless real credentials are explicitly provisioned. Nothing is promoted to production until staging is verified and evidence is recorded.

# 2. Prerequisites

- A target commit that passes all test suites (`testing/`).
- Staging environment configured per [[environment-variables.md](http://environment-variables.md)](environment-variables%20md%20559e80250b104a79b8f50e0ef8a3aa6e.md).

# 3. Steps

1. Build images from the target commit.
2. Apply staging env (real-ish config; provider in demo-mode unless credentials are provisioned).
3. Deploy:

```
docker compose -f docker-compose.yml -f docker-compose.production.yml up -d
```

1. Run migrations; seed only if required.
2. Run smoke + E2E journeys ([[e2e-test-plan.md](http://e2e-test-plan.md)](../testing/e2e-test-plan%20md%20c7de9b248c374aa099193e228167e250.md)).

# 4. Verify

| Check | Expected |
| --- | --- |
| Health | All services healthy; queues draining. |
| Storage | Signed URLs working; private media isolated (not publicly reachable). |
| Journeys | E2E journeys pass in demo-mode. |

Complete the staging verification checklist in `implementation-plan/checklists/` before considering production.

# 5. Evidence

- Record results under `implementation-plan/evidence/staging-verification/`.

# 6. Rollback

- If verification fails, do not promote; follow [[rollback.md](http://rollback.md)](rollback%20md%20ce2b95b2b57b431891c2528be2613c84.md) to restore the previous known-good state.

# 7. Related documentation

- Readiness gate: [[production-readiness.md](http://production-readiness.md)](production-readiness%20md%204a0fae0daf6e4e87ab5a75757909ec84.md). E2E: [[e2e-test-plan.md](http://e2e-test-plan.md)](../testing/e2e-test-plan%20md%20c7de9b248c374aa099193e228167e250.md). Config: [[environment-variables.md](http://environment-variables.md)](environment-variables%20md%20559e80250b104a79b8f50e0ef8a3aa6e.md).