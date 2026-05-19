# Backend Engine Security

## Auth rules

- Use Sanctum tokens for API access
- Enforce admin, vendor, and customer permissions server-side
- Keep vendor access scoped to owned shops and products

## Private data rules

- User photos are private
- YouCam credentials are private
- Storage credentials are private
- Settings JSON is private and not editable through public UI

## Route protection

- Admin routes require admin permission
- Vendor routes require vendor permission and ownership checks
- Try-on routes require auth and rate limits
- Payment, withdrawal, payout, and settlement routes stay disabled or guarded
- Vendor APIs must remain safe to expose only through the separate vendor app
