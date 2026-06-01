# Custom model strategy is fantasy at current maturity

Category: ML & Strategy
Description: Docs describe a cold-start → consented data → seller corrections → lightweight custom model → distillation path. Plausible academically but not operationally backed.
Impact: Stakeholders may expect proprietary AI differentiation long before it is realistic; requires MLOps, dataset governance, model registry, evaluation, bias testing, opt-out enforcement, and rollback. Incorrect skin/tone classification can harm trust and perceived inclusivity.
Issue ID: ISS-10
Notes & Decisions: User: move custom model to post-MVP R&D; consider multiple providers. Alternative: use provider output + seller correction as explainable attributes without training a model. — RESOLVED 2026-05-31: custom model marked post-MVP R&D only (use available providers near-term) in HLD, White Paper, Technical Proposal, and Concept Paper.
Priority: P1
Recommended Resolution: Remove custom model from the near-term roadmap; define it as research & development for post-MVP, and use available third-party providers in the interim.
Related ADRs: ADR Pack (AI/model strategy)
Related Documents: White Paper, Roadmap, Product Scope & Release Boundaries
Related Phases: Post-MVP R&D
Root Cause: ML lifecycle is described without dataset size estimates, labeling quality controls, fairness protocol, evaluation metrics, training infra, or regulatory review.
Severity: High
Status: Resolved
Target Resolution Date: May 31, 2026

**Description**

Docs describe a cold-start → consented data → seller corrections → lightweight custom model → distillation path. That is plausible academically but not operationally backed.

**Root cause**

ML lifecycle is described without dataset size estimates, labeling quality controls, fairness protocol, evaluation metrics, training infra, or regulatory review.

### Impact

- **Business:** Stakeholders may expect proprietary AI differentiation long before it is realistic.
- **Technical:** Requires MLOps, dataset governance, model registry, evaluation, bias testing, opt-out enforcement, and rollback.
- **Customer:** Incorrect skin/tone classification can harm trust and perceived inclusivity.
- **Risk if unresolved:** The “custom model” becomes a perpetual roadmap ornament.

**Recommended resolution**

Remove custom model from the near-term roadmap. Define it as research & development in post-MVP, and use available third-party providers in the interim.

**Alternative approaches**

Use provider output plus seller correction as explainable attributes without training a model.

**Related references**

- [White Paper — AI Confidence Shopping](https://app.notion.com/p/White-Paper-AI-Confidence-Shopping-36ff29d2a6b18172ab84c0006ac1ad9e?pvs=21)
- [Roadmap — Nuvia Beauty](../../implementation-plan/Roadmap%20%E2%80%94%20Nuvia%20Beauty%20d276dacddb7e4b7bb92399a12ec3c10c.md)
- [](../../product/Untitled%2089b0720ce5d2457cb678cc48369d8047.md)