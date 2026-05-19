# System Context View

This view describes the system boundary, showing how our system interacts with external actors (users) and external services.

---

## 📊 Mermaid Diagram

```mermaid
graph LR
    User[👤 Customer] --> Storefront[💻 Storefront]
    Vendor[👤 Vendor] --> VendorPortal[💻 Vendor Portal]
    Admin[👤 Admin] --> AdminPanel[💻 Admin Panel]
    
    subgraph System Boundary
        Storefront
        VendorPortal
        AdminPanel
        Backend[⚙️ Backend Engine]
        Storefront -.-> Backend
        VendorPortal -.-> Backend
        AdminPanel -.-> Backend
    end
    
    Backend --> YouCamAPI[🔌 YouCam VTO API]
    Backend --> PaymentGateway[🔌 Payment Gateway]
    Backend --> EmailService[🔌 Transactional Mailer]
```

---

## 🔌 Interface Descriptions

* **YouCam VTO API**: External REST integration used to send user photos and retrieve simulated overlay try-on results.
* **Payment Gateway**: Processes customer card payments securely.
* **Email Service**: Delivers registration, verification, and order receipt emails.
