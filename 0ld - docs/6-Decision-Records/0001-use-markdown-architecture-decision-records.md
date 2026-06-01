# ADR-0001: Use Markdown Architecture Decision Records

* **Status**: accepted
* **Deciders**: Zyro Team
* **Date**: 2026-05-19

Technical Story: Establish a standardized methodology to record critical engineering decisions.

## Context and Problem Statement

As codebase and engineering teams grow, technical decisions are frequently made during conversations, code reviews, or in meetings. Without central documentation, the rationale behind architectural choices (e.g. why we chose a specific database structure, VTO library, or deployment style) is easily lost, leading to repetitive discussions or developer confusion.

## Decision Outcome

Chosen option: **Use Markdown Architecture Decision Records (ADRs) within the version-controlled wiki repository**, because:
* It keeps the technical documentation close to the codebase.
* Changes are reviewed and version-controlled via standard Git flow (Pull Requests).
* It provides a clean, searchable history of how the system evolved.

### Consequences

* **Good / Positive**: Design reasoning is transparent, searchable, and reviewable by any team member.
* **Bad / Negative**: Adds overhead to the design process; engineers must write ADRs before implementing complex structural modifications.
* **Risks**: ADR logs can get stale if not updated when a decisions is deprecated or superseded.

---

## Pros and Cons of Options Considered

### Option 1: Markdown ADRs in the Repo

Store markdown files in a `docs/6-Decision-Records/` directory.
* **Pros**: Simple, free, zero setup, integrated with Git history.
* **Cons**: Markdown syntax must be manually validated.

### Option 2: External Documentation Site (e.g., Confluence/Notion)

Document decisions in Confluence or Notion.
* **Pros**: Rich text editing features, accessible to non-technical stakeholders.
* **Cons**: Separated from codebase, easily gets out of sync, requires access management.
