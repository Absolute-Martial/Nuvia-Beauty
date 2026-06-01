# execution-order.md

Owner: Susank Shakya

<aside>
📄

**`docs/implementation-plan/execution-order.md`**

</aside>

# Execution order

Phases run in order; a phase may not start until its dependencies are **Remote Verified** or better.

1. **Phase 0 — Baseline**
2. **Phase 1 — Storage & Recommendation** — depends on P0.
3. **Phase 2 — Mapping Editors & Events** — depends on P1.
4. **Phase 3 — Stabilization** — depends on P1+P2 verified.
5. **Phase 4 — Seller Consultation** — depends on P1+P2 verified, storage validation, seed data.
6. **Phase 5 — Perfect Corp P0** — depends on backend-only AI boundary, private media flow, no frontend creds, demo-mode fallback.
7. **Phase 6 — Demo / Push**
8. **Phase 7 — Personalized Domain Expansion**
9. **Phase 8 — Customer Self-Scan**
10. **Phase 9 — Try-On / VTO**

Full dependency detail lives on each phase page and on [docs/](../../docs%204ad284736dcb4b45b39511e90c276caf.md).