# storefront/

Owner: Susank Shakya

<aside>
📁

**`docs/storefront/`** — the customer-facing PWA: shopping flow, recommendation UI, consented self-scan, and the try-on studio.

</aside>

`storefront` (Next.js 15.5.18 / React 19.2.6, `:3003`, `@nuvia/storefront`) is the customer app. It is a thin, consent-first client that talks only to `backend-engine` and never holds provider or storage credentials ([ADR Pack](https://app.notion.com/p/ADR-Pack-36ff29d2a6b18108958af03b19f13a6e?pvs=21) ADR 0001, PWA-first). Every flow degrades gracefully to demo-mode.

# Contents

| Document | Covers |
| --- | --- |
| [[README.md](http://README.md)](storefront/README%20md%204fba39c19b5d44b3af23779d275484f8.md) | App overview, tech stack, principles, and performance targets. |
| [[customer-flow.md](http://customer-flow.md)](storefront/customer-flow%20md%200b496b07477c4900bcf9055941c5f90e.md) | The end-to-end shopping journey. |
| [[recommendation-ui.md](http://recommendation-ui.md)](storefront/recommendation-ui%20md%2030b4642b8e1d477e98c2075074d7d864.md) | How recommendations + confidence are presented. |
| [[self-scan-flow.md](http://self-scan-flow.md)](storefront/self-scan-flow%20md%20df98685b684a41189d155a835368a8a9.md) | Consented photo capture and analysis. |
| [[try-on-studio.md](http://try-on-studio.md)](storefront/try-on-studio%20md%205406b8fe754c4230a428fb6afb0911f7.md) | Virtual try-on experience (Phase 9). |

# Principles

- Consent-first for any beauty analysis.
- No credentials in the client; private media handled only via short-lived signed URLs.

[[README.md](http://README.md)](storefront/README%20md%204fba39c19b5d44b3af23779d275484f8.md)

[[customer-flow.md](http://customer-flow.md)](storefront/customer-flow%20md%200b496b07477c4900bcf9055941c5f90e.md)

[[self-scan-flow.md](http://self-scan-flow.md)](storefront/self-scan-flow%20md%20df98685b684a41189d155a835368a8a9.md)

[[try-on-studio.md](http://try-on-studio.md)](storefront/try-on-studio%20md%205406b8fe754c4230a428fb6afb0911f7.md)

[[recommendation-ui.md](http://recommendation-ui.md)](storefront/recommendation-ui%20md%2030b4642b8e1d477e98c2075074d7d864.md)