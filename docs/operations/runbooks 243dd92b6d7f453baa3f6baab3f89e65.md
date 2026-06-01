# runbooks/

Owner: Susank Shakya

<aside>
📁

**`docs/operations/runbooks/`** — step-by-step incident runbooks, each linked from a monitoring alert.

</aside>

Each runbook is a focused, do-this-now procedure for a specific failure. They are referenced directly from alerts ([[monitoring.md](http://monitoring.md)](monitoring%20md%2025dd9e9c06a94ff7998ee1271ff6a335.md)) and from the umbrella process ([[incident-response.md](http://incident-response.md)](incident-response%20md%20ad458a5261d74cd490e700293324e098.md)). Every runbook ends with verification, evidence, and follow-up.

# Contents

| Runbook | Use when |
| --- | --- |
| [[failed-deployment.md](http://failed-deployment.md)](runbooks/failed-deployment%20md%200e50ac3d42644c1e9fe80da4de2bd2c1.md) | A deployment fails or a release is unhealthy. |
| [[storage-incident.md](http://storage-incident.md)](runbooks/storage-incident%20md%202ce24157c5074f318acced1558475d53.md) | Storage outage, signed-URL failures, or suspected bucket exposure. |
| [[ai-provider-failure.md](http://ai-provider-failure.md)](runbooks/ai-provider-failure%20md%20ef02256e904340c0bddd805d3dbeb4a7.md) | The AI/Perfect Corp provider is erroring, slow, or over quota. |
| [[database-restore.md](http://database-restore.md)](runbooks/database-restore%20md%208f61552b61594964aaf3c508e2fec1ef.md) | Data loss/corruption requiring a restore. |

# How to read a runbook

- Start at **When to use** and confirm the symptom matches.
- Work the **Steps** in order; do not skip verification.
- Record **evidence** and open a post-incident review for Sev1/Sev2.

[[failed-deployment.md](http://failed-deployment.md)](runbooks/failed-deployment%20md%200e50ac3d42644c1e9fe80da4de2bd2c1.md)

[[storage-incident.md](http://storage-incident.md)](runbooks/storage-incident%20md%202ce24157c5074f318acced1558475d53.md)

[[ai-provider-failure.md](http://ai-provider-failure.md)](runbooks/ai-provider-failure%20md%20ef02256e904340c0bddd805d3dbeb4a7.md)

[[database-restore.md](http://database-restore.md)](runbooks/database-restore%20md%208f61552b61594964aaf3c508e2fec1ef.md)