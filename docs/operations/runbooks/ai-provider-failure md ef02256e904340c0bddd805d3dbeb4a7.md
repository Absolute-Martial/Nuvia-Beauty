# ai-provider-failure.md

Owner: Susank Shakya

<aside>
🤖

**`docs/operations/runbooks/ai-provider-failure.md`** · respond when the AI/Perfect Corp provider is erroring, slow, or over quota.

</aside>

# 1. When to use

The AI / Perfect Corp provider is erroring, slow, or over quota — raising analysis latency, failures, or queue backlog.

# 2. Severity

- **Sev2** when demo-mode fallback keeps core journeys working; escalate to **Sev1** only if journeys are actually broken.

# 3. Steps

1. **Confirm fallback engaged** — verify the system has degraded to demo-mode (`mode=demo`) so journeys still complete.
2. Check queue backlog + retries; ensure failed analysis jobs retry and dead-letter correctly.
3. Inspect provider error/quota signals ([[monitoring.md](http://monitoring.md)](../monitoring%20md%2025dd9e9c06a94ff7998ee1271ff6a335.md)).
4. If persistent, escalate to the provider; consider pausing live calls and staying in demo-mode.
5. Capture evidence; communicate expected impact (results are demo-mode until restored).

# 4. Verification

- Core journeys complete via demo-mode; queue backlog returns to baseline.
- When the provider recovers, live analysis resumes and fallback frequency drops.

# 5. Escalation & communication

- Open a provider ticket for sustained failures; note demo-mode coverage in stakeholder updates.

# 6. Evidence & follow-up

- Record the window, fallback behavior, and resolution; review in [[incident-response.md](http://incident-response.md)](../incident-response%20md%20ad458a5261d74cd490e700293324e098.md).

# 7. Related documentation

- Provider integration: `integrations/perfect-corp/`. Fallback: `integrations/perfect-corp/fallback-behavior.md`. Jobs: `backend-engine/queue-jobs.md`.