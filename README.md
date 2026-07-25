# 🎓 MariLMS AI — Platform LMS & Portal Ujian Multi-Tenant Berbasis AI (v1.5.0)

**MariLMS AI** adalah platform *Learning Management System* (LMS) dan evaluasi ujian digital multi-tenant berbasis Laravel 12 dengan fitur pembuatan kuis otomatis berbantuan Artificial Intelligence serta sistem pengawasan ujian anti-kecurangan terintegrasi.

---

## 📑 Daftar Isi
* [💡 Deskripsi Proyek](#-deskripsi-proyek)
* [💻 Prasyarat](#-prasyarat)
* [🚀 Instalasi](#-instalasi)
* [⚙️ Penggunaan](#-penggunaan)
* [👥 Peran Pengguna (Roles) & Fitur](#-peran-pengguna-roles--fitur)
* [🛠️ Tumpukan Teknologi & Library](#-tumpukan-teknologi--library)
* [📁 Struktur Direktori Modul](#-struktur-direktori-modul)
* [🗄️ Struktur Database](#-struktur-database)
* [🤝 Kontribusi](#-kontribusi)
* [⚖️ Lisensi](#-lisensi)

---

## 💡 Deskripsi Proyek

MariLMS AI dirancang khusus untuk memenuhi kebutuhan sekolah, perguruan tinggi, lembaga bimbingan belajar, dan institusi pelatihan profesional dalam menyelenggarakan ujian digital yang aman, efisien, dan modern.

### 🎯 Tujuan Utama & Masalah yang Diselesaikan
* **Pembuatan Kuis Instan**: Menghemat waktu pengajar dari berjam-jam menjadi kurang dari 30 detik melalui *AI Quiz Generator* (OpenRouter/OpenAI API) yang otomatis menghasilkan pertanyaan pilihan ganda, kunci jawaban, dan pembahasan ilmiah.
* **Pengawasan Ujian Anti-Cheat**: Mencegah kecurangan daring (*online cheating*) dengan perhitungan waktu terpusat dari server (*server-authoritative timer*) serta pelacakan aktivitas tab-switch/penutupan browser secara real-time.
* **Arsitektur Multi-Tenancy Hemat Biaya**: Menggunakan pendekatan *Single-Database Multi-Tenancy* (`/{tenant}/...`), memungkinkan ribuan lembaga memiliki portal mandiri tanpa perlu membuat database terpisah (sangat ramah untuk aaPanel, cPanel, maupun VPS).

---

## 💻 Prasyarat

Sebelum memasang dan menjalankan proyek ini, pastikan sistem Anda telah memenuhi prasyarat minimum berikut:

* **PHP**: Versi `>= 8.2` (dengan ekstensi `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `curl`).
* **Database**: MySQL `>= 8.0` atau MariaDB `>= 10.5`.
* **Node.js**: Versi `>= 18.0` & **NPM** `>= 9.0`.
* **Composer**: Versi `>= 2.2`.

---

## 🚀 Instalasi

Ikuti langkah-langkah berikut untuk memasang proyek MariLMS AI di lingkungan lokal Anda:

1. **Kloning Repositori**:
   ```bash
   git clone https://github.com/hndko/app_marilms_laravel12.git
   cd app_marilms_laravel12
   ```

2. **Pasang Dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Berkas Lingkungan (`.env`)**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Atur Koneksi Database pada Berkas `.env`**:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=app_marilms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan Migrasi Database & Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Kompilasi Aset Frontend**:
   ```bash
   npm run build
   ```

---

## ⚙️ Penggunaan

### 1. Menjalankan Aplikasi di Lingkungan Lokal
Gunakan perintah gabungan via Composer untuk menjalankan server lokal, Vite dev server, queue worker, dan log viewer secara bersamaan:

```bash
composer run dev
```

Atau jalankan server Laravel dan Vite secara terpisah:

```bash
# Terminal 1: Server Laravel
php artisan serve

# Terminal 2: Vite Dev Server
npm run dev
```

### 2. Kredensial Pengujian Awal (Default Credentials)

* **SuperAdmin Panel**: `http://localhost:8000/login`
  * **Email**: `admin@marilms.id`
  * **Password**: `password`
* **Owner Tenant Demo**: `http://localhost:8000/login`
  * **Email**: `owner@nusantara.sch.id`
  * **Password**: `password`
* **Participant Tenant Demo**: `http://localhost:8000/sma-nusantara/login`
  * **Email**: `budi@student.nusantara.sch.id`
  * **Password**: `password`

---

## 👥 Peran Pengguna (Roles) & Fitur

| Peran (Role) | Guard | Fitur Utama |
| :--- | :--- | :--- |
| 👑 **SuperAdmin** | `web` | Kelola Owner Lembaga, Paket Token AI, Provider AI LLM, Gateway Pembayaran, System Settings, & Activity Logs. |
| 🏢 **Owner** | `owner` | Generator Kuis AI, Manajemen Soal & Jawaban, Kelola Peserta & Password, Notifikasi WhatsApp, Beli Token, & Laporan Evaluasi. |
| 🎓 **Participant** | `participant` | Portal Ujian Tenant, Pengerjaan Kuis Anti-Cheat, Server Timer Real-time, Hasil Skor & Riwayat Nilai Instant. |

---

## 🛠️ Tumpukan Teknologi & Library

* **Backend Framework**: Laravel 12 (`laravel/framework ^12.0`)
* **Multi-Tenancy**: Stancl Tenancy (`stancl/tenancy ^3.10`) + Single-DB `TenantScope`
* **Autentikasi**: Single Consolidated `AuthController` & `login.blade.php` (`laravel/fortify ^1.37`)
* **Role & Otorisasi**: Spatie Laravel Permission (`spatie/laravel-permission ^6.25`)
* **Frontend Design**: TailAdmin Blade Templates + Tailwind CSS v4 (`@tailwindcss/vite ^4.0`)
* **Interaktivitas UI**: Alpine.js, ApexCharts, Flatpickr, FontAwesome Free (`@fortawesome/fontawesome-free ^6.7`)
* **AI Provider**: OpenRouter / OpenAI API Client via Service Layer

---

## 📁 Struktur Direktori Modul

Aplikasi MariLMS AI mengadopsi arsitektur terstruktur berbasis **Modul Peran**:

```
app/
└── Http/
    └── Controllers/
        ├── Auth/
        │   └── AuthController.php        # Consolidated Single Auth Controller
        ├── Modules/
        │   ├── SuperAdmin/                # Controller Backend SuperAdmin
        │   ├── Owner/                     # Controller Backend Owner
        │   └── Participant/               # Controller Backend Participant
        └── Webhook/

resources/
└── views/
    ├── auth/
    │   └── login.blade.php               # Consolidated Auth View Template
    ├── layouts/
    │   ├── app-auth.blade.php            # Layout Auth Fullscreen (100% Light)
    │   └── app-backend.blade.php         # Unified Backend Layout (100% Light)
    └── modules/
        ├── superadmin/                    # Views Backend SuperAdmin
        ├── owner/                         # Views Backend Owner
        └── participant/                   # Views Backend Participant
```

---

## 🗄️ Struktur Database

### A. Tabel Central (Central Manager)
* `users` — Akun SuperAdmin
* `owners` — Data Lembaga / Instansi
* `tenants` — Data Tenant & Slug (`/slug/`)
* `domains` — Domain Tenant
* `owner_token_balances` — Stok Saldo Token AI
* `token_transactions` — Riwayat Kredit / Debit Token
* `token_packages` — Katalog Paket Token
* `token_orders` — Order Transaksi Pembayaran
* `llm_providers` — Konfigurasi Provider AI (OpenRouter)
* `payment_gateway_configs` — Konfigurasi Gateway Pembayaran
* `email_gateway_configs` — Konfigurasi Email Gateway
* `whatsapp_gateway_configs` — Konfigurasi WhatsApp Gateway
* `system_settings` & `activity_logs` — Pengaturan & Log Sistem

### B. Tabel Tenant (Berisi `tenant_id` + `TenantScope`)
* `tenant_users` — Data Akun Peserta per Tenant
* `quizzes` — Data Kuis, Passing Score, Durasi, & Prompt AI
* `questions` — Soal Kuis & Tingkat Kesulitan
* `question_options` — Pilihan Jawaban & Kunci Jawaban
* `quiz_participants` — Penugasan Kuis Private
* `quiz_attempts` — Sesi Pengerjaan & Anti-Cheat Record
* `quiz_answers` — Jawaban Peserta per Attempt
* `notification_logs` — Log Pengiriman Email / WhatsApp

---

## 🤝 Kontribusi

Kontribusi selalu terbuka! Silakan lakukan *fork* pada repositori ini, buat *branch* fitur baru, dan ajukan *Pull Request* (PR) sesuai aturan *Conventional Commits*.

---

## ⚖️ Lisensi

MariLMS AI dirilis di bawah lisensi [MIT License](LICENSE).
