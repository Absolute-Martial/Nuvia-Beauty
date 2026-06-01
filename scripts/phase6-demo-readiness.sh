#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
prep_report_host="${repo_root}/backend-engine/storage/app/beauty/phase6-demo-preparation-report.json"
audit_report_host="${repo_root}/backend-engine/storage/app/beauty/phase6-demo-audit-report.json"
prep_report_container="/var/www/html/storage/app/beauty/phase6-demo-preparation-report.json"
audit_report_container="/var/www/html/storage/app/beauty/phase6-demo-audit-report.json"

shop_id=""

while [[ $# -gt 0 ]]; do
  case "$1" in
    --shop-id)
      shift
      if [[ $# -eq 0 ]]; then
        echo "Missing value for --shop-id" >&2
        exit 1
      fi
      shop_id="$1"
      ;;
    *)
      echo "Unknown argument: $1" >&2
      echo "Usage: bash scripts/phase6-demo-readiness.sh [--shop-id <id>]" >&2
      exit 1
      ;;
  esac
  shift
done

prepare_command="export DB_SOCKET=; php artisan config:clear >/dev/null; php artisan beauty:prepare-demo --report-path='${prep_report_container}'"
audit_command="php artisan beauty:audit-demo-readiness --report-path='${audit_report_container}'"

if [[ -n "${shop_id}" ]]; then
  prepare_command+=" --shop-id=${shop_id}"
  audit_command+=" --shop-id=${shop_id}"
fi

docker compose \
  -f "${repo_root}/docker-compose.yml" \
  -f "${repo_root}/docker-compose.dev.yml" \
  run --rm --no-deps backend /bin/sh -lc "${prepare_command} && ${audit_command}"

echo "Phase 6 demo preparation report: ${prep_report_host}"
echo "Phase 6 demo audit report: ${audit_report_host}"
