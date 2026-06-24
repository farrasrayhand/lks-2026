# Deployment Guide — Kaltim Smart Platform

> Deploy ke AWS menggunakan Terraform + Docker. Ikuti langkah-langkah di bawah sesuai urutan.

---

## Daftar Isi

1. [Tools yang Dibutuhkan](#1-tools-yang-dibutuhkan)
2. [Setup AWS Account](#2-setup-aws-account)
3. [Deploy dengan Terraform](#3-deploy-dengan-terraform)
4. [Setup Aplikasi di EC2](#4-setup-aplikasi-di-ec2)
5. [Setup Amazon Lex (Chatbot)](#5-setup-amazon-lex-chatbot)
6. [Cek & Test Aplikasi](#6-cek--test-aplikasi)
7. [Monitoring](#7-monitoring)
8. [Arsitektur AWS](#8-arsitektur-aws)
9. [Pengujian Mandiri dengan Postman](#9-pengujian-mandiri-dengan-postman)
10. [Checklist Pengumpulan](#10-checklist-pengumpulan)

---

## 1. Tools yang Dibutuhkan

Install semua ini sebelum mulai:

| Tool | Versi | Install |
|---|---|---|
| Terraform | >= 1.5 | [hashicorp.com](https://developer.hashicorp.com/terraform/install) |
| AWS CLI | >= 2.0 | [aws.amazon.com/cli](https://aws.amazon.com/cli/) |
| Git | any | sudah terinstall di EC2 |

---

## 2. Setup AWS Account

### Langkah 1 — Buat IAM User

1. Buka **AWS Console → IAM → Users → Create user**
2. Nama user: `kaltim-terraform`
3. Pilih **Attach policies directly** → centang `AdministratorAccess`
4. Klik **Create user**
5. Buka user yang baru dibuat → tab **Security credentials → Create access key**
6. Pilih **Command Line Interface (CLI)** → buat
7. **Catat** Access Key ID dan Secret Access Key (hanya muncul sekali!)

### Langkah 2 — Konfigurasi AWS CLI

```bash
aws configure
```

Isi saat diminta:
```
AWS Access Key ID: AKIA... (dari langkah 1)
AWS Secret Access Key: ...
Default region name: ap-southeast-1
Default output format: json
```

Verifikasi:
```bash
aws sts get-caller-identity
# Harus menampilkan Account ID dan ARN kamu
```

### Langkah 3 — Buat SSH Key Pair

1. Buka **AWS Console → EC2 → Key Pairs → Create key pair**
2. Nama: `kaltim-key`, Type: RSA, Format: `.pem`
3. File `.pem` akan terdownload otomatis
4. Simpan dan ubah permission:

```bash
mv ~/Downloads/kaltim-key.pem ~/.ssh/
chmod 400 ~/.ssh/kaltim-key.pem
```

---

## 3. Deploy dengan Terraform

### Langkah 1 — Siapkan APP_KEY dan JWT_SECRET

Jalankan ini di laptop kamu (bukan di EC2):

```bash
cd lks-kaltim-2026-[kode-peserta]

# Generate APP_KEY
php artisan key:generate --show
# Catat hasilnya: base64:xxxxx

# Generate JWT Secret
php artisan jwt:secret --show
# Catat hasilnya: xxxxx
```

### Langkah 2 — Buat File Konfigurasi Terraform

```bash
cd terraform

cat > terraform.tfvars << 'EOF'
aws_region     = "ap-southeast-1"
project_name   = "kaltim-smart-platform"
environment    = "production"
key_name       = "kaltim-key"
instance_type  = "t3.medium"
db_username    = "kaltim_admin"
db_password    = "K4lt1m#Secure2026!"
db_name        = "kaltim_smart_platform"
app_key        = "base64:..."         # ganti dengan hasil langkah 1
jwt_secret     = "..."                # ganti dengan hasil langkah 1
s3_bucket_name = "kaltim-uploads-[kode-peserta]-2026"
EOF
```

> ⚠️ Ganti nilai `app_key`, `jwt_secret`, dan `s3_bucket_name` sebelum lanjut!

### Langkah 3 — Jalankan Terraform

```bash
terraform init

terraform plan   # preview — pastikan tidak ada error

terraform apply  # ketik "yes" saat diminta
```

Tunggu **10–15 menit**. Setelah selesai, catat output-nya:

```bash
terraform output > outputs.txt
cat outputs.txt
```

Output yang penting:
- `alb_dns_name` — URL aplikasi kamu
- `rds_endpoint` — endpoint database
- `s3_bucket_name` — nama bucket S3
- `lex_bot_id` dan `lex_bot_alias_id` — untuk chatbot

---

## 4. Setup Aplikasi di EC2

### Langkah 1 — Masuk ke EC2

```bash
# Cari instance ID
aws ec2 describe-instances \
  --filters "Name=tag:Name,Values=kaltim-smart-platform-instance" \
  --query "Reservations[0].Instances[0].InstanceId" --output text

# Connect via SSM (tanpa perlu public IP)
aws ssm start-session --target i-xxxxxxxxxxxxx
```

### Langkah 2 — Install Docker dan Clone Repo

```bash
sudo yum update -y
sudo yum install -y git docker
sudo systemctl enable docker && sudo systemctl start docker
sudo usermod -aG docker ec2-user

# Clone repo
git clone https://github.com/[username]/lks-kaltim-2026-[kode-peserta].git /opt/kaltim-app
cd /opt/kaltim-app/docker
```

### Langkah 3 — Buat File .env

```bash
cat > .env << 'EOF'
APP_KEY=base64:...               # sama dengan terraform.tfvars
JWT_SECRET=...                   # sama dengan terraform.tfvars
DB_DATABASE=kaltim_smart_platform
DB_USERNAME=kaltim_admin
DB_PASSWORD=K4lt1m#Secure2026!
DB_ROOT_PASSWORD=root_secret_123
APP_PORT=80
APP_URL=http://<alb-dns-name>    # ganti dengan ALB DNS dari terraform output
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=kaltim-uploads-[kode-peserta]-2026
AWS_LEX_BOT_ID=...               # dari terraform output
AWS_LEX_BOT_ALIAS_ID=...         # dari terraform output
CACHE_STORE=redis
SESSION_DRIVER=redis
FILESYSTEM_DISK=s3
EOF
```

### Langkah 4 — Jalankan Aplikasi

```bash
docker compose up -d --build

# Cek status
docker compose ps
docker compose logs app --tail=20
```

---

## 5. Setup Amazon Lex (Chatbot)

### Langkah 1 — Buat Bot di AWS Console

1. Buka **AWS Console → Amazon Lex → Create bot**
2. Pilih **Create a blank bot**
3. Nama: `KaltimServiceBot`
4. IAM: **Create a role with basic Amazon Lex permissions**
5. Children's Privacy: **No** → Idle timeout: **5 menit** → **Next**
6. Language: **Indonesian (id_ID)** → **Done**

### Langkah 2 — Tambahkan Intent

Klik **Add intent** untuk setiap baris berikut:

| Intent | Contoh Ucapan | Respons Bot |
|---|---|---|
| GreetingIntent | "halo", "selamat pagi", "hai" | "Halo! Ada yang bisa saya bantu?" |
| KTPIntent | "cara buat KTP", "syarat e-KTP" | "Untuk membuat KTP: daftar akun → pilih Layanan → Pembuatan KTP..." |
| KKIntent | "buat kartu keluarga", "syarat KK" | "KK bisa diajukan online. Estimasi 7 hari kerja..." |
| LaporIntent | "lapor jalan rusak", "aduan sampah" | "Laporkan di menu Laporan Warga, pilih kategori..." |

### Langkah 3 — Build dan Deploy

1. Klik **Build** (tunggu ~2 menit)
2. Klik **Deploy → Create alias**, nama: `prod`

### Langkah 4 — Ambil Bot ID dan Alias ID

1. Kembali ke daftar bot → klik `KaltimServiceBot`
2. **Bot ID** ada di pojok kanan atas
3. Klik **Aliases → prod** → **Alias ID** di pojok kanan atas

### Langkah 5 — Update .env di EC2

```bash
cd /opt/kaltim-app/docker
# Edit .env, update dua baris ini:
# AWS_LEX_BOT_ID=...
# AWS_LEX_BOT_ALIAS_ID=...

docker compose up -d --build
```

---

## 6. Cek & Test Aplikasi

### Health Check

```bash
# Cek via curl
curl http://<alb-dns-name>/health

# API Health
curl http://<alb-dns-name>/api/health
# Harus return: {"success":true,"message":"All systems operational"}
```

### Test Login via Browser

Buka `http://<alb-dns-name>`:
- Admin: `admin@kaltim.go.id` / `password`
- Warga: `budi@email.com` / `password`
- Chatbot: klik bubble 💬 di kanan bawah, ketik "cara buat KTP"

### Test API via curl

```bash
# Daftar layanan
curl http://<alb-dns-name>/api/services

# Login admin
curl -X POST http://<alb-dns-name>/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@kaltim.go.id","password":"password"}'

# Gunakan token dari response login untuk endpoint lain:
curl http://<alb-dns-name>/api/dashboard/stats \
  -H "Authorization: Bearer <token>"
```

---

## 7. Monitoring

### Lihat Log Aplikasi

```bash
# Di dalam EC2 instance
cd /opt/kaltim-app/docker
docker compose logs app -f
```

### CloudWatch di AWS Console

- **EC2:** AWS Console → EC2 → Auto Scaling Groups → tab Monitoring
- **RDS:** AWS Console → RDS → Databases → tab Monitoring
- **ALB:** AWS Console → EC2 → Load Balancers → tab Monitoring

Metrik yang perlu dipantau: CPU Utilization, Database Connections, Request Count, Response Time.

---

## 8. Arsitektur AWS

![Architecture](architecture-diagram.png)

```
                      INTERNET
                          │
                          ▼
          ┌───────────────────────────────┐
          │    Application Load Balancer  │  Public Subnets (AZ1 + AZ2)
          │    (port 80/443)              │
          └───────────────┬───────────────┘
                          │
              ┌───────────┴───────────┐
              ▼                       ▼
    ┌──────────────────┐  ┌──────────────────┐
    │  EC2 App (AZ1)   │  │  EC2 App (AZ2)   │  Private App Subnets
    │  Docker +        │  │  Docker +        │
    │  PHP-FPM + Nginx │  │  PHP-FPM + Nginx │
    └───┬──────┬───────┘  └───┬──────┬───────┘
        │      │              │      │
        ▼      │              │      ▼
  ┌─────────┐  │              │  ┌─────────┐
  │   RDS   │  └──────┬───────┘  │   S3    │
  │  MySQL  │         ▼          │ Uploads │
  │ (AZ1)   │  ┌──────────────┐  └─────────┘
  └─────────┘  │ ElastiCache  │
               │   Redis      │
               └──────────────┘

  VPC 10.0.0.0/16
  ├── Public Subnets:  10.0.1.0/24, 10.0.2.0/24   (ALB)
  ├── App Subnets:     10.0.10.0/24, 10.0.11.0/24  (EC2)
  └── DB Subnets:      10.0.20.0/24, 10.0.21.0/24  (RDS + Redis)
```

| Komponen | Spesifikasi | Keterangan |
|---|---|---|
| VPC | 10.0.0.0/16 | Virtual Private Cloud |
| ALB | 1 | Application Load Balancer (public) |
| EC2 ASG | 2–4 × t3.medium | Auto Scaling, private subnet |
| RDS | db.t3.micro, MySQL 8.0 | Private subnet |
| ElastiCache | cache.t3.micro, Redis 7 | Private subnet |
| S3 | 1 bucket | Upload file, blocked public access |
| Amazon Lex | 1 bot | Chatbot Bahasa Indonesia |
| NAT Gateway | 1 | Internet untuk instance private |

---

## 9. Pengujian Mandiri dengan Postman

### Setup Postman

1. Buat **New Collection** → nama: `Kaltim Smart Platform`
2. Tambah **Collection Variables**:
   - `base_url` → `http://<alb-dns-name>` (isi dengan ALB DNS kamu)
   - `token` → (kosong, diisi setelah login)

---

### A. Test Autentikasi

**Register:**
```
POST {{base_url}}/api/auth/register
Body (JSON):
{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "phone": "08123456789",
  "address": "Jl. Test No. 1"
}
✅ status 201, success: true, data.token ada
```

**Login Admin:**
```
POST {{base_url}}/api/auth/login
Body (JSON): { "email": "admin@kaltim.go.id", "password": "password" }
✅ status 200, data.token ada
→ Copy token ke Collection variable "token"
```

**Login Warga:**
```
POST {{base_url}}/api/auth/login
Body (JSON): { "email": "budi@email.com", "password": "password" }
```

**Profile:**
```
GET {{base_url}}/api/auth/profile
Authorization: Bearer {{token}}
✅ data.role dan data.email ada
```

**Logout:**
```
POST {{base_url}}/api/auth/logout
Authorization: Bearer {{token}}
✅ success: true
```

---

### B. Test Layanan Publik

**Daftar Layanan (publik, tanpa token):**
```
GET {{base_url}}/api/services
✅ data berupa array, data[0].name ada
```

**Ajukan Layanan (sebagai warga):**
```
POST {{base_url}}/api/services/request
Authorization: Bearer <warga_token>
Body: { "service_type_id": 1, "description": "Pengajuan test" }
✅ status 201, data.status = "pending"
```

**Update Status (sebagai admin):**
```
PUT {{base_url}}/api/services/request/1/status
Authorization: Bearer <admin_token>
Body: { "status": "processing" }
✅ status 200, success: true
```

**Cek Notifikasi Warga (harus ada notif baru):**
```
GET {{base_url}}/api/notifications
Authorization: Bearer <warga_token>
✅ data.data[0].message mengandung kata "berubah"
```

---

### C. Test Laporan Warga

**Buat Laporan:**
```
POST {{base_url}}/api/reports
Authorization: Bearer <warga_token>
Body:
{
  "category": "infrastructure",
  "title": "Jalan Berlubang Test",
  "description": "Test laporan jalan berlubang",
  "location": "Jl. Test"
}
✅ status 201, data.status = "open"
```

**Lihat Laporan (admin — harus bisa lihat semua):**
```
GET {{base_url}}/api/reports
Authorization: Bearer <admin_token>
✅ data.data berisi laporan dari semua user
```

**Lihat Laporan (warga — hanya miliknya):**
```
GET {{base_url}}/api/reports
Authorization: Bearer <warga_token>
✅ semua item di data.data punya user_id yang sama
```

---

### D. Test Dashboard Admin

**Statistik:**
```
GET {{base_url}}/api/dashboard/stats
Authorization: Bearer <admin_token>
✅ data.users, data.reports, data.service_requests ada
```

**Rekapitulasi per Kategori:**
```
GET {{base_url}}/api/dashboard/reports/summary
Authorization: Bearer <admin_token>
✅ data berupa array { category, total }
```

---

### E. Test Keamanan (RBAC)

**Warga akses admin → harus 403:**
```
GET {{base_url}}/api/dashboard/stats
Authorization: Bearer <warga_token>
✅ status 403, success: false
```

**Warga update status → harus 403:**
```
PUT {{base_url}}/api/services/request/1/status
Authorization: Bearer <warga_token>
Body: { "status": "done" }
✅ status 403, success: false
```

**Tanpa token → harus 401:**
```
GET {{base_url}}/api/auth/profile
(Tanpa Authorization header)
✅ status 401
```

**S3 Block Public Access:**
```
Buka di browser: https://<s3-bucket>.s3.ap-southeast-1.amazonaws.com/
✅ Harus muncul "Access Denied"
❌ Jangan sampai muncul list file
```

---

### F. Test Health Check

**Web Health (browser):**
```
Buka: http://<alb-dns-name>/health
✅ Tampilkan "All Systems Operational"
✅ Database: OK, Cache: OK, Storage: OK
```

**API Health:**
```
GET {{base_url}}/api/health
✅ data.database.status = "ok"
✅ data.cache.status = "ok"
✅ data.storage.status = "ok"
```

---

### G. Test Chatbot

**Via browser:**
```
Buka http://<alb-dns-name> → klik 💬 → ketik: "cara buat ktp"
✅ Bot membalas dengan panduan pembuatan KTP
```

**Via API:**
```
POST {{base_url}}/api/chatbot
Body: { "message": "cara daftar akun" }
✅ reply berisi instruksi registrasi
```

---

### H. Validasi Format Response dan Pagination

**Format JSON — semua endpoint harus punya:**
```json
{ "success": true|false, "message": "...", "data": {...} }
```

**Pagination:**
```
GET {{base_url}}/api/reports?per_page=2&page=1
✅ data.current_page = 1
✅ data.per_page = 2
✅ data.data.length <= 2
✅ data.links dan data.total ada
```

### Ekspor Postman Collection

1. Klik **...** pada collection → **Export**
2. Format: **Collection v2.1**
3. Simpan sebagai: `Kaltim-Smart-Platform.postman_collection.json`

---

## 10. Checklist Pengumpulan

> Deadline: **pukul 17.00 WITA**

### Yang harus dikumpulkan:

- [ ] **URL Live** — `http://<alb-dns-name>` aktif dan bisa diakses
  - Tulis di bagian atas `README.md`
- [ ] **Postman Collection** — file `Kaltim-Smart-Platform.postman_collection.json`
  - Semua endpoint sudah di-test, response sesuai
- [ ] **Screenshot CloudWatch Dashboard**
  - Buat dashboard baru di CloudWatch
  - Tambahkan widget: EC2 CPU, ALB Request Count, RDS Connections, Response Time
  - Screenshot semua widget dalam satu layar → simpan sebagai `cloudwatch-dashboard.png`
- [ ] **CloudTrail Presigned URL**
  - Aktifkan CloudTrail jika belum (simpan log ke S3)
  - Generate presigned URL **maksimal 1 jam sebelum deadline (sekitar 16.00 WITA):**
    ```bash
    aws s3 presign s3://<cloudtrail-bucket>/AWSLogs/<account-id>/CloudTrail/<region>/<date>/ \
      --expires-in 3600
    ```
  - Simpan URL yang dihasilkan

### Update README.md sebelum kumpul:

```markdown
## Deployment Live
- **URL:** http://<alb-dns-name>
- **Health Check:** http://<alb-dns-name>/health
- **API Docs:** http://<alb-dns-name>/api-info
```

---

## Perkiraan Biaya Bulanan

| Layanan | Spesifikasi | Estimasi |
|---|---|---|
| EC2 | 2x t3.medium | ~$60 |
| RDS | db.t3.micro | ~$15 |
| ElastiCache | cache.t3.micro | ~$12 |
| ALB | 1 | ~$20 |
| NAT Gateway | 1 | ~$32 |
| S3 | 1 GB | ~$0.02 |
| Lex | ~1000 req/hari | ~$5 |
| **Total** | | **~$144/bulan** |
