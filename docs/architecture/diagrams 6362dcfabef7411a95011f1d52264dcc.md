# diagrams/

Owner: Susank Shakya

<aside>
📁

**`docs/architecture/diagrams/`** — C4-style and sequence diagrams for Nuvia Beauty.

</aside>

This folder holds the canonical visual models of the system, kept text-based (Mermaid) so they live in version control beside the docs and stay reviewable in pull requests. They follow the **C4 approach** — zooming from system context, to runtime containers, down to behavioral sequences — and are the authoritative diagrams referenced by `hld-system-architecture.md` and `reference-architecture.md`.

# Diagram set

| Diagram | C4 level | Answers |
| --- | --- | --- |
| [[context-diagram.md](http://context-diagram.md)](diagrams/context-diagram%20md%2096e5b13d3cec4e1fab877c75f87bee7f.md) | Level 1 — System Context | Who uses Nuvia Beauty and which external systems it depends on. |
| [[container-diagram.md](http://container-diagram.md)](diagrams/container-diagram%20md%2025cff974532240b99d5dbaeb34a47185.md) | Level 2 — Containers | The deployable apps and data stores, and how they connect at runtime. |
| [[sequence-diagrams.md](http://sequence-diagrams.md)](diagrams/sequence-diagrams%20md%20c94c0f41b2404b5da83dccc160261bb5.md) | Behavioral | How key flows (media, consultation, scoring, consent, insights) execute over time. |

# Conventions

- Diagrams are authored in **Mermaid**; node labels with special characters are quoted, and
`` is used for line breaks.
- All external provider and storage interactions are drawn through `backend-engine` to reflect the real trust boundary — no frontend talks to providers, databases, or storage credentials directly.
- Keep diagrams in sync with the HLD and Reference Architecture; when a flow or container changes in code, update the relevant diagram in the same change.

[[container-diagram.md](http://container-diagram.md)](diagrams/container-diagram%20md%2025cff974532240b99d5dbaeb34a47185.md)

[[context-diagram.md](http://context-diagram.md)](diagrams/context-diagram%20md%2096e5b13d3cec4e1fab877c75f87bee7f.md)

[[sequence-diagrams.md](http://sequence-diagrams.md)](diagrams/sequence-diagrams%20md%20c94c0f41b2404b5da83dccc160261bb5.md)