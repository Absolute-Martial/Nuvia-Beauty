# Backend Engine Testing

## Commands

```bash
php artisan test
php artisan route:list
```

If route discovery fails because of legacy payment controllers, document the
failure and smoke test known routes directly.

## Manual smoke checklist

- Laravel boots
- `/settings` returns expected settings
- `/products` returns product data
- `/categories` returns category data
- `/shops` returns shop data
- `/token` issues a token for a valid user
- try-on upload requires auth
- try-on task creation works with configured YouCam credentials
- vendor-owned product and shop routes enforce ownership checks
- vendor cannot access another vendor's product data
- customer cannot access admin or vendor routes
