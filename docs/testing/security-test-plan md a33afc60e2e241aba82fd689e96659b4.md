# security-test-plan.md

Owner: Susank Shakya

<aside>
🛡️

**`docs/testing/security-test-plan.md`** · verifying the zero-trust boundary — authz, consent, and private-media protection.

</aside>

# 1. Purpose & scope

This plan covers authorization, consent, and private-media protection — the zero-trust boundary ([ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0012). Tests are written deny-by-default: access is assumed refused unless explicitly granted.

# 2. What we test

| Area | Checks |
| --- | --- |
| Authentication | Unauthenticated requests rejected on protected routes. |
| Authorization | Policies enforce role boundaries (customer / seller / admin); no cross-tenant access. |
| Consent | Analysis only runs with recorded consent; withdrawal stops processing and removes inputs. |
| Private media | Direct/forged/expired URL access denied; attempts audit-logged. |
| Input validation | Boundary DTOs + Form Requests reject malformed input. |
| Redaction | Responses never contain `api_key`, `secret`, `signed_url`, or `raw_payload`. |

# 3. Approach

- **Negative tests first** (deny by default); verify audit-log entries are written for sensitive access.
- Attempt cross-tenant and cross-role access explicitly and assert denial.

# 4. Coverage & gates

- Every protected route has an unauthenticated + unauthorized test.
- Every consent-gated action has a no-consent + withdrawn-consent test.
- Redaction middleware verified on representative responses.

# 5. Evidence

- Store results under `implementation-plan/evidence/security-tests/`.

# 6. Limitations & future enhancements

- Third-party penetration testing is scheduled during production readiness, separate from this suite.

# 7. Related documentation

- Boundaries: `integrations/ai-provider/provider-boundaries.md`. Guardrails: `backend-engine/agent-guardrails.md`. Storage: [[storage-test-plan.md](http://storage-test-plan.md)](storage-test-plan%20md%204f788d4da0a543b4948e183305b3d3e3.md). Decisions: ADR 0012.