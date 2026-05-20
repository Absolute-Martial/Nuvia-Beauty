# Dokploy Deployment

Dokploy is a good fit for this repo because it handles:

- app domains
- environment variables
- container image pulls from GHCR
- host path mounts for AIStor license and credential files

## Deployment Model

Use the standalone production compose file:

- `docker-compose.production.yml`

In Dokploy, set the compose file path to `./docker-compose.production.yml`, define the runtime env vars, and mount the host files for AIStor through the env paths.

## Image Variables

Set these to the GHCR refs you want Dokploy to run:

- `NUVIA_BACKEND_IMAGE`
- `NUVIA_ADMIN_IMAGE`
- `NUVIA_VENDOR_IMAGE`
- `NUVIA_STOREFRONT_IMAGE`

Example:

```env
NUVIA_BACKEND_IMAGE=ghcr.io/absolute-martial/nuvia-beauty-backend:development
NUVIA_ADMIN_IMAGE=ghcr.io/absolute-martial/nuvia-beauty-admin-panel:development
NUVIA_VENDOR_IMAGE=ghcr.io/absolute-martial/nuvia-beauty-vendor-portal:development
NUVIA_STOREFRONT_IMAGE=ghcr.io/absolute-martial/nuvia-beauty-storefront:development
```

## AIStor Files

AIStor expects files, not pasted text, for the license and root credentials.

Create these on the host and mount them into the AIStor container:

- `AISTOR_LICENSE_FILE`
- `AISTOR_ROOT_USER_FILE`
- `AISTOR_ROOT_PASSWORD_FILE`
- `AISTOR_DATA_DIR`
- `AISTOR_CERTS_DIR`

Recommended host paths:

- `/opt/nuvia/aistor/license/minio.license`
- `/opt/nuvia/aistor/secrets/root_user`
- `/opt/nuvia/aistor/secrets/root_password`
- `/opt/nuvia/aistor/data`
- `/opt/nuvia/aistor/certs`

You can generate these files interactively with [create_aistor_files.py](../create_aistor_files.py). Run `python3 create_aistor_files.py`, choose `dokploy-host`, paste the license content, enter the root username, and enter the root password. The script writes the files into the expected host layout and creates the `data` and `certs` directories.

## Example Env

Use [`.env.dokploy.example`](../.env.dokploy.example) as the starting point for the Dokploy app environment.

## Notes

- Keep the AIStor license file out of Git.
- Keep the root user and root password files out of Git.
- Point Dokploy domain routing at the published app ports from the compose stack.
