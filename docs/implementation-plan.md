# Implementation Plan: Customer Self-Scan & Try-On Studio Phases

## Phase 8: Customer Self-Scan and Profile History

### Objective
Add controlled customer self-scan and profile snapshot history after seller consultation and personalized domain expansion are stable.

### Backend Deliverables
- Customer self-scan APIs
- Quota enforcement and ownership policies
- Append profile snapshots; preserve previous snapshots
- Regenerate recommendations from latest snapshot
- Retention and delete-request hooks

### Frontend Deliverables
- Customer self-scan PWA screens
- Camera/upload fallback and quality gate
- Analysis progress display
- Snapshot history timeline
- Recommendation refresh UI

### API Endpoints
```http
POST /api/v1/beauty/customer/scans
POST /api/v1/beauty/customer/scans/{id}/attach-media
POST /api/v1/beauty/customer/scans/{id}/analysis/start
GET  /api/v1/beauty/customer/scans/{id}/status
GET  /api/v1/beauty/customer/profile/snapshots
GET  /api/v1/beauty/customer/profile/snapshots/{id}
GET  /api/v1/beauty/customer/profile/compare?from={snapshotId}&to={snapshotId}
POST /api/v1/beauty/customer/profile/delete-request
```

### Exit Criteria
- Customers can self-scan, create new snapshots, compare past snapshots, and regenerate recommendations securely.
- Raw media and provider credentials remain protected.

## Phase 9: Try-On Studio and Makeup VTO

### Objective
Add controlled makeup virtual try-on (VTO) to improve visual purchase confidence while preserving core recommendation flow.

### Backend Deliverables
- Makeup VTO task type and provider service/jobs
- Eligibility service and result media storage
- Save/discard endpoints and demo-mode support

### Frontend Deliverables
- Try-On action on eligible recommendation cards
- Before/after slider and result gallery
- Processing and quota/cost indicators
- Vendor/admin eligibility mapping UI

### API Endpoints
```http
POST /api/v1/beauty/sessions/{id}/try-on/start
GET  /api/v1/beauty/try-on/{taskId}/status
GET  /api/v1/beauty/sessions/{id}/try-on-results
POST /api/v1/beauty/try-on-results/{id}/save
POST /api/v1/beauty/try-on-results/{id}/discard
```

### Exit Criteria
- Sellers or customers can run controlled makeup try-on for eligible products.
- Result media is private; demo mode is functional.
- No provider keys or raw images are exposed.
