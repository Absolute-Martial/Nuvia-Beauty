#!/usr/bin/env bash
set -euo pipefail

report_path=""
api_url="${API_URL:-}"
storefront_url="${STOREFRONT_URL:-}"
vendor_url="${VENDOR_URL:-}"
admin_url="${ADMIN_URL:-}"
vendor_email="${VENDOR_EMAIL:-}"
vendor_password="${VENDOR_PASSWORD:-}"
admin_email="${ADMIN_EMAIL:-}"
admin_password="${ADMIN_PASSWORD:-}"
storage_root_url="${STORAGE_ROOT_URL:-}"

while [[ $# -gt 0 ]]; do
  case "$1" in
    --api-url) shift; api_url="${1:-}" ;;
    --storefront-url) shift; storefront_url="${1:-}" ;;
    --vendor-url) shift; vendor_url="${1:-}" ;;
    --admin-url) shift; admin_url="${1:-}" ;;
    --vendor-email) shift; vendor_email="${1:-}" ;;
    --vendor-password) shift; vendor_password="${1:-}" ;;
    --admin-email) shift; admin_email="${1:-}" ;;
    --admin-password) shift; admin_password="${1:-}" ;;
    --storage-root-url) shift; storage_root_url="${1:-}" ;;
    --report-path) shift; report_path="${1:-}" ;;
    *)
      echo "Unknown argument: $1" >&2
      exit 1
      ;;
  esac
  shift
done

if [[ -z "${api_url}" || -z "${storefront_url}" || -z "${vendor_url}" || -z "${admin_url}" || -z "${vendor_email}" || -z "${vendor_password}" || -z "${admin_email}" || -z "${admin_password}" ]]; then
  echo "Missing required inputs. Set API_URL, STOREFRONT_URL, VENDOR_URL, ADMIN_URL, VENDOR_EMAIL, VENDOR_PASSWORD, ADMIN_EMAIL, and ADMIN_PASSWORD." >&2
  exit 1
fi

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
if [[ -z "${report_path}" ]]; then
  report_path="${repo_root}/backend-engine/storage/app/beauty/phase6-deployment-smoke-report.json"
fi

tmp_dir="$(mktemp -d)"
trap 'rm -rf "${tmp_dir}"' EXIT

normalize_url() {
  printf '%s' "${1%/}"
}

api_url="$(normalize_url "${api_url}")"
storefront_url="$(normalize_url "${storefront_url}")"
vendor_url="$(normalize_url "${vendor_url}")"
admin_url="$(normalize_url "${admin_url}")"
storage_root_url="$(normalize_url "${storage_root_url}")"

http_get() {
  local url="$1"
  local output="$2"
  local auth_header="${3:-}"
  if [[ -n "${auth_header}" ]]; then
    curl -fsS -H "${auth_header}" "${url}" -o "${output}"
  else
    curl -fsS "${url}" -o "${output}"
  fi
}

http_status() {
  local url="$1"
  local output="$2"
  curl -sS -L -o "${output}" -w '%{http_code}' "${url}"
}

login_token() {
  local email="$1"
  local password="$2"
  local output="$3"
  curl -fsS \
    -H 'Content-Type: application/json' \
    -X POST \
    -d "{\"email\":\"${email}\",\"password\":\"${password}\"}" \
    "${api_url}/token" \
    -o "${output}"

  php -r '$data=json_decode(file_get_contents($argv[1]), true); $token=$data["token"] ?? null; if(!$token){fwrite(STDERR, "Token login failed\n"); exit(1);} echo $token;' "${output}"
}

extract_json() {
  local file="$1"
  local expression="$2"
  php -r '$data=json_decode(file_get_contents($argv[1]), true); $expr=$argv[2]; $value=$data; foreach(explode(".", $expr) as $part){ if($part === "") continue; if(is_array($value) && array_key_exists($part, $value)){ $value=$value[$part]; } else { exit(2); } } if(is_array($value)){ echo json_encode($value, JSON_UNESCAPED_SLASHES); } else { echo $value; }' "${file}" "${expression}"
}

storefront_status_file="${tmp_dir}/storefront-status.html"
vendor_status_file="${tmp_dir}/vendor-status.html"
admin_status_file="${tmp_dir}/admin-status.html"
products_file="${tmp_dir}/products.json"
vendor_login_file="${tmp_dir}/vendor-login.json"
admin_login_file="${tmp_dir}/admin-login.json"
session_file="${tmp_dir}/session.json"
analysis_file="${tmp_dir}/analysis.json"
recommendations_file="${tmp_dir}/recommendations.json"
overview_file="${tmp_dir}/overview.json"

storefront_status="$(http_status "${storefront_url}" "${storefront_status_file}")"
vendor_status="$(http_status "${vendor_url}/beauty/consultations" "${vendor_status_file}")"
admin_status="$(http_status "${admin_url}/products/beauty-mappings" "${admin_status_file}")"
http_get "${api_url}/products" "${products_file}"

product_total="$(extract_json "${products_file}" 'total')"
if [[ "${product_total}" -lt 1 ]]; then
  echo "Expected deployed API /products to contain seeded catalog items." >&2
  exit 1
fi

vendor_token="$(login_token "${vendor_email}" "${vendor_password}" "${vendor_login_file}")"
admin_token="$(login_token "${admin_email}" "${admin_password}" "${admin_login_file}")"

http_get "${api_url}/api/v1/beauty/sessions/demo-seller-consultation" "${session_file}" "Authorization: Bearer ${vendor_token}"
session_state="$(extract_json "${session_file}" 'data.session.session_state')"
task_id="$(extract_json "${session_file}" 'data.session.ai_tasks.0.id')"
media_visibility="$(extract_json "${session_file}" 'data.session.media_asset.visibility')"
media_bucket="$(extract_json "${session_file}" 'data.session.media_asset.bucket')"
media_object_key="$(extract_json "${session_file}" 'data.session.media_asset.object_key')"

http_get "${api_url}/api/v1/beauty/analysis/${task_id}/status" "${analysis_file}" "Authorization: Bearer ${vendor_token}"
analysis_status="$(extract_json "${analysis_file}" 'data.task.status')"
recommendation_count="$(php -r '$data=json_decode(file_get_contents($argv[1]), true); echo count($data["data"]["recommendations"] ?? []);' "${analysis_file}")"

http_get "${api_url}/api/v1/beauty/sessions/demo-seller-consultation/recommendations" "${recommendations_file}" "Authorization: Bearer ${vendor_token}"
overview_query_url="${api_url}/api/v1/admin/beauty/product-mappings/overview"
http_get "${overview_query_url}" "${overview_file}" "Authorization: Bearer ${admin_token}"
ready_count="$(extract_json "${overview_file}" 'data.summary.ready_products')"

private_media_public_status="skipped"
if [[ -n "${storage_root_url}" ]]; then
  private_media_public_status="$(curl -sS -o /dev/null -w '%{http_code}' "${storage_root_url}/${media_bucket}/${media_object_key}" || true)"
fi

php -r '
$report = [
  "checked_at" => date(DATE_ATOM),
  "api_url" => $argv[1],
  "storefront_url" => $argv[2],
  "vendor_url" => $argv[3],
  "admin_url" => $argv[4],
  "checks" => [
    "storefront_http_status" => (int) $argv[5],
    "vendor_route_http_status" => (int) $argv[6],
    "admin_route_http_status" => (int) $argv[7],
    "product_total" => (int) $argv[8],
    "session_state" => $argv[9],
    "analysis_status" => $argv[10],
    "recommendation_count" => (int) $argv[11],
    "ready_products" => (int) $argv[12],
    "media_visibility" => $argv[13],
    "private_media_public_status" => $argv[14],
  ],
];
file_put_contents($argv[15], json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
' \
"${api_url}" \
"${storefront_url}" \
"${vendor_url}" \
"${admin_url}" \
"${storefront_status}" \
"${vendor_status}" \
"${admin_status}" \
"${product_total}" \
"${session_state}" \
"${analysis_status}" \
"${recommendation_count}" \
"${ready_count}" \
"${media_visibility}" \
"${private_media_public_status}" \
"${report_path}"

echo "Phase 6 deployment smoke report: ${report_path}"
