# Feature Flags

Feature flags should control rollout, not security.

## Expected flags

- photo try-on enabled
- checkout enabled
- shipping enabled
- admin settings editor enabled
- vendor finance enabled

## Rules

- Backend must enforce disabled features
- Frontends can use flags for display only
- Keep `vendor finance enabled` off while payout, wallet, withdrawal,
  commission, and settlement flows remain disabled for MVP
- Add new flags to `.env.example` and this file
