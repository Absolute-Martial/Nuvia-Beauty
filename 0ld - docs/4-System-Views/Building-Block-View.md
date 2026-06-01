# Building Block View

This view shows the internal decomposition of the system into sub-components, services, and libraries.

---

## 🏛️ System Decomposition Diagram

```mermaid
graph TD
    subgraph Frontend Layer
        Storefront[Next.js Storefront]
        AdminPanel[Next.js / Laravel Admin Portal]
        VendorPortal[Next.js / Laravel Vendor Portal]
    end

    subgraph Core API Layer
        API[Laravel REST API]
        AuthSvc[Auth / Guard Service]
        CatalogSvc[Catalog / Product Manager]
        OrderSvc[Order Processing Engine]
        VTOSvc[VTO Orchestration Module]
    end

    subgraph Storage Layer
        DB[(PostgreSQL / MySQL)]
        Redis[(Redis Cache & Queue)]
        S3[(AWS S3 / Local Media Storage)]
    end

    Storefront --> API
    AdminPanel --> API
    VendorPortal --> API

    API --> AuthSvc
    API --> CatalogSvc
    API --> OrderSvc
    API --> VTOSvc

    AuthSvc --> DB
    CatalogSvc --> DB
    CatalogSvc --> Redis
    OrderSvc --> DB
    VTOSvc --> S3
```

---

## 📦 Component Descriptions

* **Next.js Storefront**: Interactive client portal for browsing products, managing cart, and invoking VTO try-on options.
* **Laravel REST API**: Core backend engine handling authentication, business logic, authorization, database operations, and job queues.
* **VTO Orchestration Module**: Integrates with external VTO APIs, handles image upload buffering, and coordinates the processing states.
