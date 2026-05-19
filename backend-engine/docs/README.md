# ⚙️ Backend Engine Documentation

This folder documents the Laravel API and commerce engine that powers the Nuvia Beauty application suite.

---

## 🛠️ Tech Stack

* **Language**: PHP `8.3`
* **Framework**: Laravel `13`
* **Package Name**: `nuvia/beauty-api`
* **Local Package Path**: `packages/marvel`

---

## 💻 Developer Commands

| Action | Command | Description |
| --- | --- | --- |
| **Install Dependencies** | `composer install` | Installs backend dependencies. |
| **Run Dev Server** | `php artisan serve --host 0.0.0.0 --port 8000` | Exposes Laravel API on port `8000`. |
| **Run Test Suite** | `php artisan test` | Executes Laravel PHPUnit test cases. |
| **Storage Bootstrap** | `php artisan marvel:aws-setup` | Configures S3/MinIO compatible storage. |

---

## 🔑 Core Configuration

All configurations are handled via the main `.env` file:

| Parameter | Purpose / Notes |
| --- | --- |
| `APP_NAME` | Application name branding (e.g. `Nuvia Beauty`). |
| `APP_ENV` | Environment state (`production`, `local`, `testing`). |
| `DB_CONNECTION` | Database driver (e.g. `mysql`). |
| `REDIS_HOST` | Host URL for Redis queues and cache storage. |
| `FILESYSTEM_DISK` | Storage driver configuration. |
| `AISTOR_ENDPOINT` | MinIO/AIStor custom endpoints. |

---

## 📦 Storage & File System

* **Media Disks**: Uploaded product media uses the configured `MEDIA_DISK`.
* **CSV Imports**: Engineered to run via a local temp file bridge to ensure compatibility with S3-compatible cloud storage.
* **Compatibility**: Configured natively for path-style endpoints (e.g. MinIO/AIStor).

---

## 📂 Key Architecture Paths

* 🛠️ `packages/marvel/src/Rest/Routes.php` — Core package REST endpoint registration.
* 🎮 `packages/marvel/src/Http/Controllers/` — Core controllers.
* 📁 `routes/api.php` — Primary endpoint entry routing.
* 📦 `config/filesystems.php` — Storage driver configuration file.

> [!IMPORTANT]
> When routes, environment keys, or file storage operations are modified, ensure this file is updated first and synchronized with the repository-wide documentation in `/docs/`.
