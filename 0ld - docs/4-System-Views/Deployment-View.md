# Deployment View

This view maps the software components to the physical hosting infrastructure.

---

## 🌐 Infrastructure Architecture Diagram

Below is the standard layout for single-server VPS deployment managed using **Dokploy**.

```mermaid
graph TD
    User[🌍 User Browser] --> HTTPS[🔒 HTTPS / Port 443]

    subgraph VPS Host Server
        HTTPS --> Traefik[🛡️ Traefik Reverse Proxy]

        subgraph Dokploy Managed Containers
            Traefik --> SF[Storefront Container]
            Traefik --> BE[Backend Engine Container]
            Traefik --> ADM[Admin Panel Container]

            BE --> Postgres[(PostgreSQL Container)]
            BE --> RedisContainer[(Redis Queue Container)]
        end
    end

    BE -.-> S3[📦 External AWS S3 / MinIO Storage]
```

---

## ⚙️ Deployment Specifications

* **Traefik Reverse Proxy**: Automatically handles SSL (Let's Encrypt) and routes traffic to respective containers.
* **Dokploy**: Orchestrates builds, handles environment variables, updates config files, and exposes deployments via webhooks.
* **Storage Backup**: PostgreSQL container writes automated backups to a mounted host volume daily.
