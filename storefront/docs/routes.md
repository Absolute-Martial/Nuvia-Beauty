# Storefront Routes

## Public routes

- `/`
- `/products/[slug]`
- `/shops/[slug]`
- `/categories/[slug]`
- `/cart`
- `/checkout`
- `/try-on`

## Notes

- The storefront is public.
- The homepage lives at `/`.
- Try-on flows should call the backend through the shop-origin proxy.
- Private admin or vendor routes must never be surfaced here.
