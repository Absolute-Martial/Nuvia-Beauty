# orchestration.md

Owner: Susank Shakya

<aside>
🎛️

**`docs/integrations/ai-provider/orchestration.md`** · how `backend-engine` orchestrates AI analysis and recommendation scoring end to end.

</aside>

# 1. Purpose & scope

This page defines how the backend **orchestrates AI work** — turning a consented capture into a stored analysis result and a fresh set of recommendations — without ever putting a provider call in the request path or exposing credentials to clients. All provider orchestration is backend-only by decision ([ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0002), and recommendations are deterministic first (ADR 0004).

# 2. Orchestration pipeline

The canonical provider pattern: **validate → resolve media → create task → dispatch → provider call → store → snapshot → regenerate → reconcile**.

```mermaid
flowchart TD
	start(["Analysis requested"]) --> val["Validate session + consent + quota"]
	val --> media["Resolve media by private object key"]
	media --> task["Create beauty_ai_tasks row (queued)"]
	task --> job["Dispatch background job (Redis queue)"]
	job --> call["Backend-only provider call via adapter"]
	call --> poll{"Healthy result?"}
	poll -- Yes --> store["Store beauty_analysis_results"]
	poll -- No --> fb["Fallback chain (see fallback-behavior)"]
	fb --> store
	store --> snap["Write beauty_profile_snapshot"]
	snap --> rec["Regenerate deterministic recommendations"]
	rec --> quota["Reconcile quota + audit"]
	quota --> done(["Result + recommendations ready"])
```

# 3. Mechanics

- **Queued** — provider calls run as background jobs on the Redis queue, never in the HTTP request path.
- **Idempotent jobs** — each job is keyed to a `beauty_ai_tasks` row so retries do not double-charge quota or duplicate results.
- **Retries** — bounded retries with exponential backoff on transient errors.
- **Timeouts** — strict per-call timeouts; exceeding them triggers the fallback chain.
- **Caching** — stable results are cached where safe to cut cost and latency.
- **Quota reconciliation** — quota is debited on dispatch and reconciled on completion/failure against `beauty_quota_accounts`/`beauty_quota_events`.

# 4. Provider strategy

Providers sit behind an adapter that exposes the internal contract, so callers never branch on provider identity (see `provider-boundaries.md` and `request-response-contract.md`). The resolution order on failure is:

```
primary provider (Perfect Corp P0)
  -> secondary provider (if configured)
    -> deterministic demo-mode / safe defaults
```

# 5. Output

Scoring yields the canonical recommendation payload consumed across the apps:

```json
{
  "product_id": "prod_123",
  "score": 0.82,
  "confidence": 0.74,
  "reasons": ["matches oily-skin profile", "fragrance-free"],
  "warnings": ["patch test recommended"]
}
```

Analysis attributes are persisted in `beauty_analysis_results` and snapshotted into `beauty_profile_snapshots`; recommendations are regenerated deterministically from the snapshot.

# 6. Failure handling

- Any provider timeout, error, or quota exhaustion routes through the fallback chain and ultimately demo-mode / safe defaults.
- The task records the final `mode` (live / demo) and reason; downstream handling is identical regardless of mode.
- Repeated failures alert operations via `runbooks/ai-provider-failure.md`.

# 7. Monitoring

- Track task throughput, retry rate, fallback rate, and provider latency.
- A rising fallback rate signals provider degradation before users notice.

# 8. Related documentation

- Boundaries: `provider-boundaries.md`. Safety: `safety-rules.md`. Contract: `perfect-corp/request-response-contract.md`. Fallback: `perfect-corp/fallback-behavior.md`.
- Flow: `architecture/diagrams/sequence-diagrams.md` (recommendation scoring). Decisions: ADR 0002, 0004, 0012.