# Operational readiness is weak

Category: DevOps & Delivery, Operations
Description: Deployment docs mention Docker Compose, GHCR, Dokploy, AIStor files, ports, and verification commands — but monitoring, alerting, rollback, backup/restore, incident response, RPO/RTO, and runbooks are not mature.
Impact: Pilot failures will be slow to diagnose; no clear observability for provider/queue/media-deletion/consent/recommendation failures. Users experience failures with no support path.
Issue ID: ISS-13
Notes & Decisions: Overlaps with ISS-23 (deployment manual assumptions) and ISS-24 (observability).
Priority: P0
Recommended Resolution: Add a production readiness checklist: logs, metrics, alerts, backups, restore drill, rollback, incident owner.
Related ADRs: —
Related Documents: deployment README, production-readiness
Related Phases: Phase 8–9 (ops/readiness)
Root Cause: Deployment is treated as “can run containers,” not “can operate a production service.”
Severity: High
Status: Open

**Description**

Deployment docs mention Docker Compose, GHCR, Dokploy, AIStor files, ports, and verification commands. But monitoring, alerting, rollback, backup/restore, incident response, RPO/RTO, and runbooks are not mature.

**Root cause**

Deployment is treated as “can run containers,” not “can operate a production service.”

### Impact

- **Business:** Pilot failures will be slow to diagnose.
- **Technical:** No clear observability for provider failures, queue failures, media deletion failures, consent violations, or recommendation errors.
- **Customer:** Users experience failures with no support path.
- **Risk if unresolved:** Demo works, pilot fails.

**Recommended resolution**

Add a production readiness checklist: logs, metrics, alerts, backups, restore drill, rollback, incident owner.

**Related references**

- [[README.md](http://README.md)](../../deployment/README%20md%20877b8d43b0694960aa6008fb576f0016.md)
- [[production-readiness.md](http://production-readiness.md)](../../deployment/production-readiness%20md%204a0fae0daf6e4e87ab5a75757909ec84.md)