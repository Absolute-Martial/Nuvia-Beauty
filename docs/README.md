# Nuvia Beauty Documentation Hub

This repository is split into four runtime surfaces plus shared deployment files:

- `backend-engine` - Laravel 13 API and commerce engine
- `admin-panel` - Next.js admin console on port `3002`
- `storefront` - Next.js storefront on port `3003`
- `vendor-portal` - Next.js vendor console on port `3004`

Start here:

- [Backend Engine docs](../backend-engine/docs/README.md)
- [Admin Panel docs](../admin-panel/docs/README.md)
- [Vendor Portal docs](../vendor-portal/docs/README.md)
- [Storefront docs](../storefront/docs/README.md)
- [Route Inventory](./routes.md)
- [Deployment](./deployment.md)
- [Dokploy](./dokploy.md)
- [Wiki Home](./Home.md)

## GitHub Wiki Publishing

- The repository publishes docs to the GitHub wiki through `.github/workflows/publish-wiki.yml`.
- The workflow uses [`cmbrose/github-docs-to-wiki`](https://github.com/cmbrose/github-docs-to-wiki) to sync the assembled docs tree.
- Initialize the repository wiki once in GitHub by creating the first page before running the sync workflow.
- The workflow keeps wiki page names path-based so sibling docs with the same heading do not collide.
- Configure a repository secret named `WIKI_SYNC_SECRET` with repo access so the workflow can push into the wiki repository.
- The repo markdown files remain the source of truth; the wiki is a published mirror built from these docs folders.

## Current Stack

- Backend: PHP `8.3`, Laravel `13`
- Frontend: Next.js `15.5.18`, React `19.2.6`
- Package manager: Yarn classic `1.22.22`
- Object storage: S3-compatible storage with MinIO/AIStor support

## Working Rule

Keep these docs aligned with the real files in the repo. When the page tree, scripts, ports, env names, or deployment surface changes, update the matching doc file in the relevant app `docs/` folder and mirror any repo-wide changes here.
