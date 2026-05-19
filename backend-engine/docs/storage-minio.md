# Storage and MinIO

## Private paths

- Settings JSON: `storage/app/private/zyro/settings/zyro.settings.json`
- Source photos: `storage/app/private/zyro/try-on/source-photos`
- Try-on results: `storage/app/private/zyro/try-on/results`

## Rules

- Store uploaded user photos privately
- Use UUID filenames
- Validate MIME type and size
- Serve private media through signed URLs or controlled API responses
- Never expose storage credentials to frontend apps

