# 🔌 YouCam VTO Integration Proxy

The Backend Engine acts as the exclusive secure gateway communicating with Perfect Corp (YouCam) Virtual Try-On (VTO) APIs.

---

## 🎨 Supported Try-On Families

* 👕 **AI Clothes VTO**: Virtual apparel dressing overlays.
* ⌚ **AI Watch VTO**: Smart wear simulation.
* 👜 **AI Bag VTO**: Handbag placement rendering.
* 👟 **AI Shoes VTO**: Footwear trial alignment.

---

## 🔄 Task Lifecycle Flow

```mermaid
sequenceDiagram
    autonumber
    participant App as ⚙️ Backend Engine
    participant DB as 🗄️ Database
    participant API as 🔌 Perfect Corp API
    
    App->>API: POST /tasks (Send User Photo + Model parameters)
    API-->>App: Return Task ID & Status: pending
    App->>DB: Store Task Record (task_id, status: pending)
    
    loop Status Polling (Every 1-2 seconds)
        App->>API: GET /tasks/{task_id}
        API-->>App: Return Task Status (pending / running / completed)
        alt is completed
            App->>DB: Update Task status: completed
            Note over App: Fetch rendered image & proxy save to S3
        else is failed
            App->>DB: Update Task status: failed
        end
    end
```

---

## 🛡️ Integration Security Rules

1. **Secrets Security**: The `YOUCAM_API_KEY` and authorization tokens must remain strictly server-side. Never expose these tokens to frontends or client logs.
2. **Rate Limiting**: Apply throttles to task creation and photo upload routes to prevent API credential lockouts and minimize billing overages.
3. **Data Verification**: Enforce file type and mime validation (e.g. `image/jpeg`, `image/png`) before making external API requests.
4. **Secret Shielding**: Log all third-party API errors without printing raw request headers containing secrets.
