# Queue/async architecture is underdeveloped

Category: Architecture, Reliability
Description: Provider calls, analysis polling, quota reconciliation, media deletion, and recommendation regeneration are inherently async, but docs do not adequately define queue-worker deployment, retry policies, dead-letter handling, idempotency, or task state machine.
Impact: Failed tasks block consultations; duplicate jobs, stuck jobs, inconsistent snapshots, double quota charges, orphaned media. Users see indefinite “processing” states.
Issue ID: ISS-15
Notes & Decisions: Approach: keep analysis synchronous only for demo, clearly labeled non-production.
Priority: P0
Recommended Resolution: Design the task lifecycle explicitly: pending, uploaded, queued, processing, provider_failed, fallback_used, completed, expired, discarded.
Related ADRs: ADR Pack (async/queues)
Related Documents: Reference Architecture, Implementation Plan
Related Phases: Phase 5 (analysis tasks)
Root Cause: Async workflow is described at the happy-path sequence level.
Severity: High
Status: Open

**Description**

Provider calls, analysis polling, quota reconciliation, media deletion, and recommendation regeneration are inherently async. But docs do not adequately define queue worker deployment, retry policies, dead-letter handling, idempotency, or task state machine.

**Root cause**

Async workflow is described at the happy-path sequence level.

### Impact

- **Business:** Failed tasks will block consultations.
- **Technical:** Duplicate jobs, stuck jobs, inconsistent snapshots, double quota charges, orphaned media.
- **Customer:** Users see indefinite “processing” states.
- **Risk if unresolved:** Consultation flow becomes unreliable under real use.

**Recommended resolution**

Design the task lifecycle explicitly: pending, uploaded, queued, processing, provider_failed, fallback_used, completed, expired, discarded.

**Approaches**

Keep analysis synchronous only for demo, clearly labeled non-production.

**Related references**

- [Reference Architecture](https://app.notion.com/p/Reference-Architecture-36ff29d2a6b181a3a17ad985ffef1d0c?pvs=21)
- [implementation-plan/](../../implementation-plan%20dcdd3415ed5441189d05d8599585c8ac.md)