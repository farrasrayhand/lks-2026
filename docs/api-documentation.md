# API Documentation - Kaltim Smart Platform

## Base URL
```
http://localhost:8080/api
```

## Response Format

Semua endpoint mengembalikan response dengan format:
```json
{
  "success": true|false,
  "message": "Deskripsi",
  "data": { ... }
}
```

## Pagination

Endpoint list mendukung pagination dengan query parameter:
- `per_page` (default: 10, max: 100)
- `page` (default: 1)

## Authentication

Semua endpoint yang membutuhkan autentikasi menggunakan JWT token di header:
```
Authorization: Bearer <token>
```

---

## A. Modul Autentikasi

### Register
```
POST /auth/register
```

**Request Body:**
| Field | Type | Required | Description |
|---|---|---|---|
| name | string | Yes | Nama lengkap |
| email | string | Yes | Email (unique) |
| password | string | Yes | Password (min 6 karakter) |
| phone | string | No | Nomor telepon |
| address | string | No | Alamat |

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": { "id": 1, "name": "...", "email": "...", "role": "citizen" },
    "token": "eyJ..."
  }
}
```

### Login
```
POST /auth/login
```

**Request Body:**
| Field | Type | Required |
|---|---|---|
| email | string | Yes |
| password | string | Yes |

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": { "user": {...}, "token": "eyJ..." }
}
```

### Logout
```
POST /auth/logout
Authorization: Bearer <token>
```

### Profile
```
GET /auth/profile
Authorization: Bearer <token>
```

---

## B. Modul Layanan Publik

### Daftar Jenis Layanan
```
GET /services
```
Public endpoint, tanpa autentikasi.

### Ajukan Permintaan Layanan
```
POST /services/request
Authorization: Bearer <token>
```

| Field | Type | Required | Description |
|---|---|---|---|
| service_type_id | integer | Yes | ID jenis layanan |
| description | string | No | Deskripsi permintaan |
| attachment_url | string | No | URL lampiran (opsional) |
| attachment | file | No | Upload file langsung (jpg/png/pdf, max 5MB) |

### Detail Permintaan
```
GET /services/request/{id}
Authorization: Bearer <token>
```
- Admin: bisa lihat semua permintaan
- Citizen: hanya permintaan miliknya

### Update Status (Admin Only)
```
PUT /services/request/{id}/status
Authorization: Bearer <token>  (Role: admin)
```

| Field | Type | Values |
|---|---|---|
| status | string | pending, processing, done, rejected |

> Setiap perubahan status otomatis mengirim notifikasi ke pengguna.

### Daftar Semua Permintaan
```
GET /services/requests
Authorization: Bearer <token>
```
Query: `?status=pending&per_page=10&page=1`

---

## C. Modul Laporan Warga

### Kirim Laporan
```
POST /reports
Authorization: Bearer <token>
```

| Field | Type | Required | Description |
|---|---|---|---|
| category | string | Yes | infrastructure, environment, social, other |
| title | string | Yes | Judul laporan |
| description | string | Yes | Deskripsi detail |
| location | string | No | Lokasi kejadian |
| image_url | string | No | URL foto (opsional) |
| image | file | No | Upload foto langsung (jpg/png, max 5MB) |

### Daftar Laporan
```
GET /reports
Authorization: Bearer <token>
```
- Admin: melihat semua laporan semua warga
- Citizen: hanya laporan miliknya sendiri
- Query: `?category=infrastructure&status=open`

### Detail Laporan
```
GET /reports/{id}
Authorization: Bearer <token>
```

### Update Laporan
```
PUT /reports/{id}
Authorization: Bearer <token>
```
| Field | Type | Description |
|---|---|---|
| category | string | (opsional) |
| title | string | (opsional) |
| description | string | (opsional) |
| location | string | (opsional) |
| status | string | open, in_progress, resolved (admin only) |

---

## D. Modul Notifikasi

### Daftar Notifikasi
```
GET /notifications
Authorization: Bearer <token>
```
Query: `?per_page=10&page=1`

---

## E. Modul Dashboard Admin

### Statistik Ringkasan
```
GET /dashboard/stats
Authorization: Bearer <token>  (Role: admin)
```
```json
{
  "success": true,
  "data": {
    "users": { "total": 3, "citizens": 2, "admins": 1 },
    "reports": { "total": 3, "open": 2, "in_progress": 1, "resolved": 0 },
    "service_requests": { "total": 2, "pending": 1, "processing": 1, "done": 0, "rejected": 0 }
  }
}
```

### Rekapitulasi Laporan per Kategori
```
GET /dashboard/reports/summary
Authorization: Bearer <token>  (Role: admin)
```
```json
{
  "success": true,
  "data": [
    { "category": "infrastructure", "total": 5 },
    { "category": "environment", "total": 3 }
  ]
}
```

---

## F. Health Check

### Cek Kesehatan Sistem (JSON)
```
GET /health
```
```json
{
  "success": true,
  "message": "All systems operational",
  "data": {
    "database": { "status": "ok", "connection": "mysql" },
    "cache": { "status": "ok", "driver": "redis" },
    "storage": { "status": "ok", "disk": "local" }
  }
}
```
Return 200 jika semua OK, 503 jika ada layanan down.

---

## G. Chatbot API

### Kirim Pesan Chat
```
POST /chatbot
Content-Type: application/json
```

| Field | Type | Required |
|---|---|---|
| message | string | Yes |

```json
// Request
{ "message": "cara buat KTP" }

// Response
{ "reply": "Untuk membuat KTP, daftar akun dulu..." }
```

Mode:
- Jika `AWS_LEX_BOT_ID` diset → menggunakan Amazon Lex (AI)
- Jika tidak → fallback rule-based
