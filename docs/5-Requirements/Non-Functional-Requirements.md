# Non-Functional Requirements

Quality attributes, technical constraints, and operating limits for the system.

---

## ⚡ Performance

* **NFR-P-01 (Response Time)**: Storefront page loads must achieve a First Contentful Paint (FCP) of under 1.5 seconds.
* **NFR-P-02 (API Latency)**: REST API endpoints must return responses within 300ms (excluding external VTO image generation).

---

## 🔒 Security & Privacy

* **NFR-S-01 (Data Encryption)**: All data in transit must be encrypted using TLS 1.3. passwords must be hashed using bcrypt (rounds = 10+).
* **NFR-S-02 (VTO Privacy)**: User-uploaded face photos must be deleted automatically after 24 hours unless explicitly saved by the user.

---

## 📈 Reliability & Scalability

* **NFR-R-01 (Availability)**: The system target availability is 99.9% uptime per calendar month.
* **NFR-R-02 (Horizontal Scale)**: The REST API must support horizontal scaling with sticky session routing or stateless token auth.
