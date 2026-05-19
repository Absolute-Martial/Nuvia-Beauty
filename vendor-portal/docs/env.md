# Vendor Portal Environment Variables

| Name | Purpose | Safe for frontend | Required | Example |
| --- | --- | --- | --- | --- |
| `NEXT_PUBLIC_REST_API_ENDPOINT` | Backend API URL | Yes | Yes | `http://localhost:8000` |
| `NEXT_PUBLIC_SHOP_URL` | Storefront URL used for preview links | Yes | Yes | `http://localhost:3003` |
| `APPLICATION_MODE` | App runtime mode | No | Yes | `development` |
| `NEXT_PUBLIC_AUTH_TOKEN_KEY` | Browser auth token key name | Yes | Yes | `AUTH_CRED` |
| `NEXT_PUBLIC_DEFAULT_LANGUAGE` | Default locale | Yes | Yes | `en` |
| `NEXT_PUBLIC_ENABLE_MULTI_LANG` | Enables multilingual UI | Yes | No | `false` |
| `NEXT_PUBLIC_AVAILABLE_LANGUAGES` | Language list | Yes | No | `en` |
| `NEXT_PUBLIC_GOOGLE_MAP_API_KEY` | Optional map key | Yes | No | empty |
| `NEXT_PUBLIC_API_BROADCAST_DRIVER` | Broadcast driver label | Yes | No | `log` |
| `NEXT_PUBLIC_PUSHER_DEV_MOOD` | Pusher development toggle | Yes | No | `false` |
| `NEXT_PUBLIC_PUSHER_APP_KEY` | Pusher browser key | Yes | No | empty |
| `NEXT_PUBLIC_PUSHER_APP_CLUSTER` | Pusher cluster | Yes | No | empty |
| `NEXT_PUBLIC_BROADCAST_AUTH_URL` | Broadcast auth endpoint | Yes | No | `http://localhost:8000/broadcasting/auth` |
| `NEXT_PUBLIC_VERSION` | UI version label | Yes | Yes | `6.8.0` |

## Rules

- Keep private admin credentials out of this file.
- Do not place Cloudflare tokens, backend secrets, or service API keys in
  frontend env variables.
