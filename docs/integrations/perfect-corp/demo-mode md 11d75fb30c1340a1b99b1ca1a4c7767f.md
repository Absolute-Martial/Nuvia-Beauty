# demo-mode.md

Owner: Susank Shakya

<aside>
🎭

**`docs/integrations/perfect-corp/demo-mode.md`** · the deterministic fallback that lets every flow run without live credentials.

</aside>

# 1. Purpose & scope

Demo-mode lets every flow run **without live Perfect Corp credentials**, so demos are reliable and reproducible and core flows never depend on provider availability. It is both an explicit mode for demos/local dev and the terminal step of the fallback chain.

# 2. Behavior

- Returns **stable, deterministic** analysis for a given input (same input → same result).
- Produces output in the **same contract** as live mode (`request-response-contract.md`), so downstream code is identical.
- Carries `mode=demo` for audit, but downstream handling does not branch on it.

# 3. Determinism

Results are derived deterministically from the input (e.g. a hash of the object key seeds a fixed attribute profile), guaranteeing repeatable demos:

```
objectKey -> stable seed -> fixed attribute profile -> normalized AnalysisResult(mode=demo)
```

# 4. When it runs

- **Explicitly** — toggled for demos/local dev via `AI_DEMO_MODE=true` or the settings-system demo-mode flag.
- **Automatically** — as the final fallback on provider timeout/error/quota (`fallback-behavior.md`).

# 5. Guarantees

- No external calls; no credentials required.
- Clearly flagged internally (`mode=demo`) so results are never mistaken for live analysis.
- Same capture-quality and consent gates still apply (`ai-provider/safety-rules.md`).

# 6. Limitations

- Demo results are representative, not real analysis; they must never be presented to a customer as a live result.
- Demo-mode is for demos, development, and resilience — not a substitute for provider coverage in production.

# 7. Related documentation

- Contract: `request-response-contract.md`. Fallback: `fallback-behavior.md`. API: `p0-analysis-api.md`. MVP demo scope: `product/demo-scenarios.md`, `product/mvp-boundary.md`.