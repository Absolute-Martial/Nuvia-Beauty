# Vendor Portal Security

## Access

- Admin requires application auth
- Public deployments should also require Cloudflare Access or equivalent
- Admin should not be exposed through public storefront tunnels

## Role boundaries

- Admin can manage platform-level data
- Vendor access belongs in the separate vendor dashboard
- Customer accounts must not reach admin routes

## Secrets

- Never expose API keys, payment secrets, YouCam keys, MinIO credentials,
  Cloudflare tokens, or Flagsmith server keys in the admin bundle.
