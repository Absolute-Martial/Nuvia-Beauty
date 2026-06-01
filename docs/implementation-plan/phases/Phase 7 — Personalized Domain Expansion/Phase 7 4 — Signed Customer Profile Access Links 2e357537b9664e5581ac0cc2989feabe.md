# Phase 7.4 — Signed Customer Profile Access Links

Owner: Susank Shakya

<aside>
📦

**`docs/implementation-plan/phases/phase-7/phase-7.4-signed-customer-profile-access-links.md`** · Source: [Refrence Docs](https://app.notion.com/p/Refrence-Docs-36ff29d2a6b1804fb252e9f637032093?pvs=21). Version 0.1.0.

</aside>

**Breadcrumb:** [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md) › [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) › **Phase 7.4**

## Overview

Introduces signed, expiring access links that let customers securely view their beauty profile across personalized domains without full account auth, storing only token hashes.

## Objectives

- Generate signed, expiring profile access links.
- Enforce access policy on link use.
- Store only token hashes (never raw tokens).

## Scope

**In scope**

- `SignedCustomerProfileLink`, `CustomerProfileAccessPolicy`; `beauty_profile_access_tokens` (hashes only).

**Out of scope**

- Customer profile UI ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)); broader privacy/audit ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)).

## Business Context

Shareable, secure profile links improve customer experience while preserving privacy and minimizing auth friction.

## Functional Requirements

- Issue time-limited signed links.
- Validate + authorize on access; revoke/expire.

## Technical Requirements

- Signed token generation + verification.
- `beauty_profile_access_tokens` stores hashes, expiry, scope.
- Access policy enforcing tenant + scope.

## Architecture Impact

- Adds a token-based access path scoped to tenant + profile.

## Dependencies

- [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md).

## Detailed Implementation Tasks

- [ ]  Implement `SignedCustomerProfileLink` (issue/verify).
- [ ]  Create `beauty_profile_access_tokens` (hashes only).
- [ ]  Implement `CustomerProfileAccessPolicy` (tenant + scope + expiry).
- [ ]  Support revocation/expiry.
- [ ]  Security tests (tampering, expiry, scope).

## Deliverables

- Signed link service + token store + access policy + tests.

## Testing & Validation Strategy

- Tests for valid/expired/tampered tokens + scope enforcement.

## Acceptance Criteria

- Only valid, in-scope, unexpired links grant profile access.

## Exit Criteria

- Signed access merged and security-tested.

## Risks & Mitigations

- **Token leakage/replay** → hashes only, expiry, scope, revocation.

## Rollout Plan

- Behind tenant rollout; audited ([Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md)).

## Success Metrics

- 0 unauthorized profile accesses; tokens never stored raw.

## Related Documentation

- Parent: [Phase 7 — Personalized Domain Expansion](../Phase%207%20%E2%80%94%20Personalized%20Domain%20Expansion%203762cf29b9064237867b0b7f5b839146.md) · Hub: [[index.md](http://index.md)](../../index%20md%20c5df4ebe2dfa40a1aa88a1b6e7546929.md)
- Security: [Request for Comments (RFC)](https://app.notion.com/p/Request-for-Comments-RFC-411c875deb6e4bb79489bf2e536e41cc?pvs=21) · Schema: [[database-schema.md](http://database-schema.md)](../../../backend-engine/database-schema%20md%207af46af094054b8a99a1f95b10ab3bc8.md)

## Future Considerations

- Customer-managed link revocation UI.