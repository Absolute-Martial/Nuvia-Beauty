# Runtime View

This view describes how components interact at runtime to execute specific business processes.

---

## 🔄 Example Flow: Virtual Try-On (VTO) Sequence

The following sequence diagram outlines how a user uploads a photo to try on a cosmetic or fashion product.

```mermaid
sequenceDiagram
    autonumber
    actor Customer as 👤 Customer
    participant Client as 💻 Storefront App
    participant Backend as ⚙️ Backend Engine
    participant S3 as 📦 Media Storage (S3)
    participant YouCam as 🔌 YouCam VTO API
    
    Customer->>Client: Select Product & Upload Face Photo
    Client->>Backend: POST /api/vto/tryon (Photo + Product details)
    Backend->>S3: Upload raw image
    S3-->>Backend: Return file path/URL
    
    Backend->>YouCam: Trigger VTO render request (User Image URL + Product Model Details)
    Note over YouCam: Process image rendering & apply virtual overlays
    YouCam-->>Backend: Return rendered VTO image data / URL
    
    Backend->>S3: Save processed VTO image
    S3-->>Backend: Return processed file path/URL
    
    Backend-->>Client: Return HTTP 200 (VTO image URL)
    Client-->>Customer: Render try-on preview on screen
```
