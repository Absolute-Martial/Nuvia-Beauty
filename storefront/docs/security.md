# Storefront Security

## Rules

- Browser code must not contain private API keys or server secrets
- Public env vars should only contain values safe for the client bundle
- Try-on uploads go through the backend, not directly to third-party services
- Image hosts must be allowlisted before rendering
- Checkout and payment actions stay disabled while payment modules are off
- Vendor-only operations must not be exposed through the storefront
