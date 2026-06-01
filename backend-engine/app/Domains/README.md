# Domain Modules

`app/Domains/` is the backend's module boundary.

Rules:

- Put business-specific models, services, DTOs, jobs, policies, and controllers inside a domain folder.
- Keep top-level `app/Models` limited to framework-shell concerns that are not yet domain-owned.
- Add new backend capability under an existing domain first; create a new domain only when the responsibility is clearly separate.

Current implemented domains:

- `Beauty`
- `Storage`
- `Audit`
