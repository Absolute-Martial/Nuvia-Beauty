# Nuvia Beauty Wiki

Nuvia Beauty is a multivendor AI beauty commerce platform with four runtime surfaces and shared deployment infrastructure, built on the Zyro Fashion base.

---

## 🧭 Navigation

### 1. 🎯 Foundational Scope
* [Goals and Non-Goals](1-Goals-and-Non-Goals.md) — Business objectives, project scope, and out-of-scope boundaries.
* [Context, Scope, and Stakeholder](2-Context,-Scope-and-Stakeholder.md) — External interfaces, system boundary, and key stakeholders.
* [Constraints and Risks](3-Constraints-and-Risks.md) — Technical, regulatory, and business constraints alongside the project risk register.

### 2. 🏛️ Architectural Views
* [Architecture Overview](architecture.md) — High-level structural description of the application.
* [System Context View](4-System-Views/Context-View.md) — System boundaries and external connections.
* [Building Block View](4-System-Views/Building-Block-View.md) — Internal subsystem decomposition.
* [Runtime View](4-System-Views/Runtime-View.md) — Sequence diagrams of critical runtime flows.
* [Deployment View](4-System-Views/Deployment-View.md) — Infrastructure topology.
  * [VPS Deployment](deployment.md) — Step-by-step VPS hosting setup guide.
  * [Dokploy Setup](dokploy.md) — Dokploy continuous deployment.
* [Domain Model](4-System-Views/Domain-Model.md) — Core business entity mapping.
* [Storage Architecture](storage.md) — File uploads, S3 storage, and media handling specifications.
* [Service Inventory](services.md) — Technical list of Laravel service integrations.
* [Route Inventory](routes.md) — Complete API endpoints mapping.

### 3. 📱 Application Modules
* [Backend Engine](backend-engine/README.md)
* [Admin Panel](admin-panel/README.md)
* [Vendor Portal](vendor-portal/README.md)
* [Storefront](storefront/README.md)

### 4. 📋 Requirements & Decisions
* [Functional Requirements](5-Requirements/Functional-Requirements.md) — Detailed feature requirements.
* [Non-Functional Requirements](5-Requirements/Non-Functional-Requirements.md) — Quality attributes (performance, scale, security).
* [Architecture Decision Records (ADRs)](6-Decision-Records/0001-use-markdown-architecture-decision-records.md) — Log of design choices.

### 5. 📚 References
* [Glossary](Glossary.md) — Terminology dictionary.
* [Templates Directory](Templates/) — Blueprint files for new specifications.
* [Changelog Log](changelog.md) — Version history tracking.
