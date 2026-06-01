# monitoring.md

Owner: Susank Shakya

<aside>
📈

**`docs/operations/monitoring.md`** · what we watch to keep Nuvia Beauty healthy, and how alerts route to runbooks.

</aside>

# 1. Purpose & scope

This page defines the **observability signals, alerts, and dashboards** for the platform. Monitoring exists to detect availability, performance, and safety problems early, and to route each alert to the runbook that resolves it.

# 2. Signals

| Signal | What we watch |
| --- | --- |
| Availability | Health of `backend-engine`, the 3 frontends, MySQL, Redis, and storage. |
| Queues | Backlog depth, job failure rate, retries, dead-letters. |
| Providers | Latency, error/quota rate, demo-mode fallback frequency. |
| Storage | Signed-URL errors, denied access attempts. |
| Errors | App error rates and latency percentiles (p50/p95/p99). |

# 3. Alerts

| Condition | Severity | Runbook |
| --- | --- | --- |
| Service down / failing health checks | Sev1 | [[failed-deployment.md](http://failed-deployment.md)](runbooks/failed-deployment%20md%200e50ac3d42644c1e9fe80da4de2bd2c1.md) |
| Queue backlog growth | Sev2 | [[ai-provider-failure.md](http://ai-provider-failure.md)](runbooks/ai-provider-failure%20md%20ef02256e904340c0bddd805d3dbeb4a7.md) |
| Sustained provider failures | Sev2 | [[ai-provider-failure.md](http://ai-provider-failure.md)](runbooks/ai-provider-failure%20md%20ef02256e904340c0bddd805d3dbeb4a7.md) |
| Spike in denied storage access | Sev1 | [[storage-incident.md](http://storage-incident.md)](runbooks/storage-incident%20md%202ce24157c5074f318acced1558475d53.md) |

# 4. Dashboards & SLOs

- A health dashboard surfaces availability, queue depth, provider mode, and storage errors at a glance.
- SLOs: API availability and p95 latency targets; demo-mode fallback is expected to keep core journeys functional even when a provider is degraded.

# 5. Related documentation

- Incidents: [[incident-response.md](http://incident-response.md)](incident-response%20md%20ad458a5261d74cd490e700293324e098.md). Runbooks: [runbooks/](runbooks%20243dd92b6d7f453baa3f6baab3f89e65.md). Guardrails: `backend-engine/agent-guardrails.md`.