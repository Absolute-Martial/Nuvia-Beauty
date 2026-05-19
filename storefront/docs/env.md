# Storefront Environment Variables

| Name | Purpose | Safe for browser | Required | Example |
| --- | --- | --- | --- | --- |
| `NEXT_PUBLIC_SITE_URL` | Canonical storefront URL | Yes | Yes | `http://localhost:3003` |
| `NEXT_PUBLIC_REST_API_ENDPOINT` | Backend API base URL | Yes | Yes | `http://localhost:8000` |
| `NEXT_PUBLIC_DEFAULT_LANGUAGE` | Default locale | Yes | Yes | `en` |
| `NEXT_PUBLIC_ENABLE_MULTI_LANG` | Enables language switching | Yes | No | `false` |
| `NEXT_PUBLIC_AVAILABLE_LANGUAGES` | Supported locales | Yes | No | `en,de` |
| `NEXTAUTH_URL` | NextAuth base URL | No | Yes | `http://localhost:3003` |
| `SECRET` | Session/auth secret | No | Yes | random value |
| `NEXT_PUBLIC_GOOGLE_MAP_API_KEY` | Map widget key | Yes | No | empty |
| `NEXT_PUBLIC_INSTAGRAM_BASIC_DISPLAY_USER_TOKEN` | Instagram widget token | Yes | No | empty |
| `NEXT_PUBLIC_MAILCHIMP_URL` | Mailchimp form endpoint | Yes | No | empty |
| `NEXT_PUBLIC_INSTAGRAM_URL` | Instagram profile link | Yes | No | empty |
| `NEXT_PUBLIC_VERSION` | UI version label | Yes | Yes | `6.8.0` |

## Rules

- Anything marked `No` must not be exposed to the browser.
- Do not add payment gateway keys while the MVP keeps payment flows disabled.
- Backend API URLs should point at the live API or local proxy, not a private secret.
