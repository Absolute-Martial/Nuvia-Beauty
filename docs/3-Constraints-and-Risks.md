# Constraints and Risks

Document the technical, organizational, and regulatory constraints, as well as the active risk register.

---

## ⚙️ Constraints

### Technical Constraints
* **Tech Constraint 1**: (e.g. Must support deployment in isolated Docker containers via Dokploy/VPS).
* **Tech Constraint 2**: (e.g. Backend must be developed using Laravel 11 / PHP 8.2+).

### Business & Organizational Constraints
* **Business Constraint 1**: (e.g. Zero-downtime deployment for the storefront during database migrations).

### Regulatory & Compliance Constraints
* **Compliance Constraint 1**: (e.g. GDPR compliance for storing user uploaded face photos in VTO).

---

## ⚠️ Risk Register

| ID | Description | Severity | Probability | Mitigation Strategy |
| --- | --- | --- | --- | --- |
| **R-01** | (e.g. API limit reached on YouCam VTO) | High | Medium | Implement local caching of processed try-on images and error-handling fallbacks. |
| **R-02** | (e.g. Database load spikes during peak shopping hours) | Medium | Low | Deploy replica databases and set up caching using Redis. |
