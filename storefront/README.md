# Nuvia Beauty Storefront

Customer-facing storefront for the Nuvia Beauty platform.

## What this repo is

- Public shop UI
- Product browsing and product detail pages
- Try-on entry points and result views
- Cart preview and checkout UI shell

## How to run it locally

```bash
yarn install
yarn dev
```

The storefront runs on port `3003` by default.

## How to build it

```bash
yarn build
```

## How to test it

```bash
yarn build
```

Then smoke test:

- home page
- category pages
- product detail pages
- try-on modal
- cart preview

## How to deploy it

- Set the storefront env values
- Point the storefront at the backend API
- Build and run the Next.js app in production mode
- Keep browser-safe env values only on the client side
