# Workers

Queue workers should handle slow or retryable work.

## Candidates

- YouCam task polling
- result image persistence
- notification delivery
- webhook handling
- long-running imports or exports

## Rules

- Do not block HTTP requests on long provider tasks in production
- Track failed jobs
- Keep worker secrets server-side

