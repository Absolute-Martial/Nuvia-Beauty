# deployment/

Owner: Susank Shakya

<aside>
📁

**`docs/deployment/`** — local setup, staging deployment, production readiness, environment configuration, and rollback.

</aside>

This folder documents how Nuvia Beauty is run and shipped — from a local demo-mode stack, to a verified staging environment, to a gated production release. The system ships as a Docker Compose stack of `backend-engine`, the three frontends, MySQL, Redis, and S3-compatible storage. Every promotion is gated by tests and recorded evidence (see `testing/` and `implementation-plan/`).

# Contents

| Document | Covers |
| --- | --- |
| [[README.md](http://README.md)](deployment/README%20md%20877b8d43b0694960aa6008fb576f0016.md) | Stack topology, compose files, and the path to production. |
| [[local-setup.md](http://local-setup.md)](deployment/local-setup%20md%206abbaa6a9f474ae4b51fc1edba2ca4ac.md) | Run the full stack locally in demo-mode. |
| [[staging-deployment.md](http://staging-deployment.md)](deployment/staging-deployment%20md%200c15e6899cab4bc5ba37bca6571938e3.md) | Deploy and verify on staging. |
| [[production-readiness.md](http://production-readiness.md)](deployment/production-readiness%20md%204a0fae0daf6e4e87ab5a75757909ec84.md) | The go/no-go gate before production. |
| [[environment-variables.md](http://environment-variables.md)](deployment/environment-variables%20md%20559e80250b104a79b8f50e0ef8a3aa6e.md) | Configuration reference and secret handling. |
| [[rollback.md](http://rollback.md)](deployment/rollback%20md%20ce2b95b2b57b431891c2528be2613c84.md) | Restore the last known-good state quickly. |

# Path to production

1. [[local-setup.md](http://local-setup.md)](deployment/local-setup%20md%206abbaa6a9f474ae4b51fc1edba2ca4ac.md) — run the full stack locally.
2. [[staging-deployment.md](http://staging-deployment.md)](deployment/staging-deployment%20md%200c15e6899cab4bc5ba37bca6571938e3.md) — deploy + verify on staging.
3. [[production-readiness.md](http://production-readiness.md)](deployment/production-readiness%20md%204a0fae0daf6e4e87ab5a75757909ec84.md) — go/no-go gate.
4. [[environment-variables.md](http://environment-variables.md)](deployment/environment-variables%20md%20559e80250b104a79b8f50e0ef8a3aa6e.md) and [[rollback.md](http://rollback.md)](deployment/rollback%20md%20ce2b95b2b57b431891c2528be2613c84.md) support every stage.

# Principles

- Same image promoted across environments; only configuration changes.
- No secrets in code or images; configuration via environment only.
- Demo-mode by default — the stack runs without live provider credentials.

[[README.md](http://README.md)](deployment/README%20md%20877b8d43b0694960aa6008fb576f0016.md)

[[local-setup.md](http://local-setup.md)](deployment/local-setup%20md%206abbaa6a9f474ae4b51fc1edba2ca4ac.md)

[[production-readiness.md](http://production-readiness.md)](deployment/production-readiness%20md%204a0fae0daf6e4e87ab5a75757909ec84.md)

[[staging-deployment.md](http://staging-deployment.md)](deployment/staging-deployment%20md%200c15e6899cab4bc5ba37bca6571938e3.md)

[[environment-variables.md](http://environment-variables.md)](deployment/environment-variables%20md%20559e80250b104a79b8f50e0ef8a3aa6e.md)

[[rollback.md](http://rollback.md)](deployment/rollback%20md%20ce2b95b2b57b431891c2528be2613c84.md)