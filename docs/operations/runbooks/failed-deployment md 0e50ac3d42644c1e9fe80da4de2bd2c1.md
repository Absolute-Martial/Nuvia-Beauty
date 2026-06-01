# failed-deployment.md

Owner: Susank Shakya

<aside>
📦

**`docs/operations/runbooks/failed-deployment.md`** · recover when a deployment fails or a release is unhealthy.

</aside>

# 1. When to use

A deployment fails, or a freshly released version is unhealthy — failing health checks, a broken core journey, or a storage/security regression after rollout.

# 2. Severity

- **Sev1** if customer-facing journeys are down or private media is at risk; otherwise **Sev2**.

# 3. Steps

1. Confirm the symptom + scope; check the health dashboard and recent deploy.
2. **Stop the rollout** and freeze further changes.
3. Roll back to the previous known-good image per [[rollback.md](http://rollback.md)](../../deployment/rollback%20md%20ce2b95b2b57b431891c2528be2613c84.md).
4. If the release migrated the database, restore from backup ([[database-restore.md](http://database-restore.md)](database-restore%20md%208f61552b61594964aaf3c508e2fec1ef.md)) rather than reversing schema blindly.
5. Capture evidence; open an incident if user-facing.

# 4. Verification

- All services healthy; queues draining.
- Core journeys pass; signed URLs work; private media remains isolated.

# 5. Escalation & communication

- Page the incident lead for Sev1; update stakeholders for user-facing impact.

# 6. Evidence & follow-up

- Record the timeline and fix; run a post-incident review ([[incident-response.md](http://incident-response.md)](../incident-response%20md%20ad458a5261d74cd490e700293324e098.md)).

# 7. Related documentation

- Rollback: [[rollback.md](http://rollback.md)](../../deployment/rollback%20md%20ce2b95b2b57b431891c2528be2613c84.md). Staging: `deployment/staging-deployment.md`. Monitoring: [[monitoring.md](http://monitoring.md)](../monitoring%20md%2025dd9e9c06a94ff7998ee1271ff6a335.md).