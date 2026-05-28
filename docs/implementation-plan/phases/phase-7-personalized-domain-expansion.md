# Phase 7: Personalized Domain Expansion

## Status

```text
Planned after demo/push readiness and after Phase 4-6 validation gates pass.
```

## Objective

Create the domain, routing, tenant, branding, and customer-profile foundation needed to make Nuvia Beauty feel personalized for each shop and each returning customer without breaking the MVP boundary.

This phase does not replace the seller-assisted MVP. It turns the validated seller consultation flow into a branded, reusable customer experience that can run on shop-specific domains or subdomains.

## Why this phase exists

Nuvia Beauty needs a separate domain strategy because the beauty experience is not only a product page. It contains customer beauty profiles, seller consultation sessions, private media, AI analysis results, saved recommendations, shop-specific branding, and future retention features.

The personalized domain layer must support:

```text
shop-specific branding
shop-specific customer entry links
seller-assisted consultation continuation
returning customer profile reopen
private media access control
vendor-specific catalogue and recommendation scope
future customer self-scan without exposing provider keys
```

## Scope

### In scope

```text
dedicated beauty domain or subdomain plan
shop subdomain routing
tenant resolution middleware
vendor branding configuration
customer profile share/reopen links
public/private route separation
DNS and reverse proxy documentation
security and privacy rules for personalized routes
fallback behavior for unknown domains
```

### Out of scope

```text
full customer self-scan
native mobile app
subscription billing
public social feed
cross-profile prediction
medical or clinical diagnosis
multi-provider AI routing
full marketplace checkout rebuild
```

## Recommended domain model

Use one platform beauty domain first, then add shop-specific subdomains.

```text
beauty.nuvia.example              platform beauty landing and customer entry
shop-slug.beauty.nuvia.example    shop-specific beauty experience
admin.nuvia.example/beauty        admin beauty controls
api.nuvia.example/api/v1/beauty   backend beauty APIs
```

If a separate second-level domain is preferred, keep the same structure:

```text
nuvia-beauty.example
shop-slug.nuvia-beauty.example
api.nuvia-beauty.example
```

Do not start with custom vendor domains. Add custom domains only after subdomain routing, tenant isolation, and SSL automation are stable.

## Route ownership

```text
beauty/ PWA
  - public landing
  - customer saved profile reopen
  - seller/kiosk route group if deployed inside same PWA

vendor-portal/
  - seller consultation management
  - consultation start/resume
  - shop-owned session history

admin-panel/
  - provider/demo toggle
  - quota overview
  - product mapping overview
  - failed task list

backend-engine/
  - tenant resolution
  - beauty sessions
  - profile snapshots
  - recommendations
  - media authorization
  - audit logs
```

## Tenant resolution rules

Add backend middleware or service logic that resolves tenant context from the request.

Resolution order:

```text
1. authenticated shop/vendor context if present
2. verified shop subdomain
3. signed customer profile token
4. explicit shop_id only for internal/admin API calls
5. reject ambiguous request
```

Rules:

```text
Do not trust frontend-provided shop_id alone.
Do not allow one vendor to read another vendor's beauty sessions.
Do not allow signed customer links to expose raw media directly.
Do not allow unknown subdomains to fall through into a default shop.
```

## Backend requirements

Add or verify the following backend capabilities.

```text
ShopDomainResolver service
BeautyTenantContext DTO
TenantResolved middleware
ShopBeautySetting model or config source
SignedCustomerProfileLink service
CustomerProfileAccessPolicy
VendorBeautyAccessPolicy
Audit events for profile-link creation and access
```

Suggested tables or fields:

```text
shop_beauty_domains
  id
  shop_id
  domain
  type: platform_subdomain | custom_domain
  status: pending | active | suspended
  verified_at
  created_at
  updated_at

shop_beauty_settings
  id
  shop_id
  display_name
  logo_url
  primary_color
  secondary_color
  welcome_copy
  support_contact
  is_enabled
  created_at
  updated_at

beauty_profile_access_tokens
  id
  profile_id
  shop_id
  token_hash
  expires_at
  revoked_at
  last_used_at
  created_by_type
  created_by_id
  created_at
```

Security note:

```text
Store token hashes, not raw tokens.
Show raw token only once when generating the customer link.
```

## Frontend requirements

### Beauty PWA

Add domain-aware rendering.

```text
read tenant branding from backend
render shop logo/name/colors
show shop-specific saved recommendation cards
show customer profile summary from signed access token
show clear expired/revoked link state
show fallback landing page for unknown domains
```

### Vendor portal

Add seller actions.

```text
view configured shop beauty URL
copy customer profile reopen link after saved session
regenerate/revoke customer access link
preview shop-branded customer page
edit basic shop beauty branding if permission allows
```

### Admin panel

Add admin controls.

```text
view shop beauty domains
activate/suspend shop beauty domain
view domain verification status
view provider/demo mode per environment
view failed tenant resolution audit events
```

Keep admin scope narrow. Do not build full analytics in this phase.

## API requirements

Suggested backend endpoints:

```http
GET  /api/v1/beauty/tenant/resolve
GET  /api/v1/beauty/shop-settings
PUT  /api/v1/beauty/shop-settings
POST /api/v1/beauty/profiles/{id}/access-links
POST /api/v1/beauty/profile-access/{token}/revoke
GET  /api/v1/beauty/profile-access/{token}
GET  /api/v1/admin/beauty/domains
POST /api/v1/admin/beauty/domains/{id}/activate
POST /api/v1/admin/beauty/domains/{id}/suspend
```

Public customer-profile access must return only normalized, retail-safe profile and recommendation data.

Allowed response data:

```text
profile summary
latest snapshot summary
recommendation cards
reason/warning text
shop branding
session date
```

Blocked response data:

```text
raw provider payload
raw image bytes
full private signed media URLs unless explicitly authorized
Perfect Corp keys
MinIO/S3 credentials
internal audit metadata
other customers' sessions
```

## DNS and deployment requirements

Document the DNS pattern.

```text
beauty.nuvia.example        CNAME or A record to reverse proxy
*.beauty.nuvia.example      wildcard record for shop subdomains
api.nuvia.example           API route to backend-engine
```

Reverse proxy requirements:

```text
preserve Host header
forward X-Forwarded-Host
forward X-Forwarded-Proto
terminate TLS safely
block unknown admin hostnames
rate-limit public profile reopen route
```

SSL requirements:

```text
wildcard certificate for platform subdomains
custom domain certificate automation later
HTTPS-only cookies
secure SameSite cookie policy
```

## Privacy and safety rules

```text
No raw image is public.
No customer profile is accessible without auth or signed token.
Customer links expire.
Customer links can be revoked.
Signed profile page must not include clinical wording.
Recommendation copy must stay retail-safe.
Audit profile link creation, use, revoke, and failed access.
```

## Validation checklist

Backend:

```text
[ ] tenant resolver identifies shop from subdomain
[ ] unknown subdomain returns safe not-found state
[ ] vendor cannot access another vendor's profile/session
[ ] signed customer token opens only allowed profile data
[ ] expired token is rejected
[ ] revoked token is rejected
[ ] audit events are written
```

Frontend:

```text
[ ] beauty PWA renders shop branding
[ ] vendor can copy customer reopen link
[ ] customer reopen page shows saved recommendation cards
[ ] expired/revoked states are clear
[ ] no raw provider payload is visible
[ ] no private credentials are visible in network responses
```

Infrastructure:

```text
[ ] wildcard DNS resolves
[ ] TLS certificate works
[ ] reverse proxy preserves Host header
[ ] public routes are rate-limited
[ ] admin routes remain protected
```

## Exit criteria

```text
A vendor has a working shop-specific beauty URL.
A saved seller consultation can generate a customer reopen link.
The customer can reopen a branded profile/recommendation page without retaking a photo.
Tenant isolation is enforced server-side.
No raw media or provider credentials are exposed.
Docs explain domain setup, route ownership, and privacy behavior.
```

## Implementation prompt

```text
Implement Phase 7: Personalized Domain Expansion for Nuvia Beauty.

Repository structure:
- backend-engine/ = Laravel backend
- beauty/ or storefront/ = customer-facing PWA depending on current repo state
- vendor-portal/ = seller/vendor frontend
- admin-panel/ = admin frontend

Goal:
Add a personalized domain/subdomain foundation so each shop can expose a branded beauty profile/recommendation experience.

Rules:
- Do not build full customer self-scan.
- Do not add native mobile.
- Do not expose Perfect Corp keys.
- Do not expose S3/MinIO credentials.
- Do not expose raw provider payloads.
- Do not expose raw images publicly.
- Do not make medical or clinical claims.
- Do not trust frontend shop_id alone.

Backend tasks:
1. Add tenant/domain resolution service.
2. Add shop beauty settings model/table if missing.
3. Add shop beauty domain model/table if missing.
4. Add signed customer profile access token service.
5. Add access policies for vendor/admin/customer profile access.
6. Add audit events for profile-link create/access/revoke/failure.
7. Add API routes for tenant resolve, shop settings, profile access link create/revoke/read.

Frontend tasks:
1. Add domain-aware branding fetch.
2. Add vendor action to copy saved profile reopen link.
3. Add customer saved profile/recommendation page.
4. Add expired/revoked/unknown-domain states.
5. Keep UI simple and branded.

Admin tasks:
1. Add minimal domain list/status page if routing supports it.
2. Add activate/suspend domain actions if backend supports it.
3. Do not build full analytics.

Infrastructure/docs:
1. Document DNS pattern.
2. Document reverse proxy requirements.
3. Document TLS/cookie/security requirements.
4. Update docs/changelog.md.

Return:
- files changed
- migrations added
- routes added
- env variables added
- DNS/reverse proxy steps
- test commands
- security notes
- known gaps
```

## Known risks

```text
wildcard DNS and TLS may vary by hosting provider
custom vendor domains need verification workflow and certificate automation
signed customer links need careful expiry/revocation handling
subdomain routing can break if reverse proxy does not preserve Host header
branding settings must not be loaded from untrusted frontend input
```
