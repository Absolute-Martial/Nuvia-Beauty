# Deployment model relies on too many manual assumptions

Category: DevOps & Delivery
Description: Deployment docs require environment variables, mounted AIStor credential/license files, GHCR credentials, Docker Compose modes, Dokploy configuration, and domain routing.
Impact: Deployment fragility during demos/pilots; misconfigured env or secrets can break storage, backend, frontend API routing, or provider access. Outages or broken media flows.
Issue ID: ISS-23
Notes & Decisions: Alternative: use managed S3 and a managed database for the pilot to reduce infrastructure risk. Overlaps with ISS-13.
Priority: P1
Recommended Resolution: Add one-command staging deployment validation and environment diff checks.
Related ADRs: —
Related Documents: deployment README, environment-variables, staging-deployment
Related Phases: Phase 8–9 (deploy)
Root Cause: Production operational model is assembled from components, not hardened.
Severity: High
Status: Open

**Description**

Deployment docs require environment variables, mounted AIStor credential/license files, GHCR credentials, Docker Compose modes, Dokploy configuration, and domain routing.

**Root cause**

Production operational model is assembled from components, not hardened.

### Impact

- **Business:** Deployment fragility during demos or pilots.
- **Technical:** Misconfigured env or secrets can break storage, backend, frontend API routing, or provider access.
- **Customer:** Outages or broken media flows.
- **Risk if unresolved:** “Works on my machine / demo host” syndrome.

**Recommended resolution**

Add one-command staging deployment validation and environment diff checks.

**Alternative approaches**

Use managed S3 and a managed database for the pilot to reduce infrastructure risk.

**Related references**

- [[README.md](http://README.md)](../../deployment/README%20md%20877b8d43b0694960aa6008fb576f0016.md)
- [[environment-variables.md](http://environment-variables.md)](../../deployment/environment-variables%20md%20559e80250b104a79b8f50e0ef8a3aa6e.md)
- [[staging-deployment.md](http://staging-deployment.md)](../../deployment/staging-deployment%20md%200c15e6899cab4bc5ba37bca6571938e3.md)