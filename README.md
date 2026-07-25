# 🎓 MariLMS AI — Portal Evaluasi & Ujian Berbasis AI Multi-Tenant (v1.4.0)

Platform Learning Management System (LMS) modern berbasis Laravel 12 dengan dukungan arsitektur **Single-Database Multi-Tenant**, pembuatan soal otomatis berbantuan Artificial Intelligence (LLM OpenRouter), serta sistem keamanan ujian anti-cheat terintegrasi.

---

## 📑 Daftar Isi
- [Deskripsi Proyek](#-deskripsi-proyek)
- [Fitur Utama & Peran Pengguna (Roles)](#-fitur-utama--peran-pengguna-roles)
- [Tumpukan Teknologi (Tech Stack) & Library](#-tumpukan-teknologi-tech-stack--library)
- [Struktur Database & Arsitektur Single-Database Tenancy](#-struktur-database--arsitektur-single-database-tenancy)
- [Prasyarat Sistem](#-prasyarat-sistem)
- [Panduan Instalasi Lokal](#-panduan-instalasi-lokal)
- [Setup Queue Worker (`php artisan queue:work`)](#-setup-queue-worker-php-artisan-queuework)
- [Panduan Deployment (aaPanel, VPS, Shared Hosting)](#-panduan-deployment)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

---

## 💡 Deskripsi Proyek

**MariLMS AI** adalah solusi platform pendidikan digital kelas enterprise yang dirancang khusus untuk sekolah, perguruan tinggi, lembaga bimbingan belajar, maupun institusi pelatihan profesional. Platform ini mengadopsi konsep **Path-Based Single-Database Multi-Tenancy** (`/{tenant}/...`) di mana seluruh data tenant tersimpan aman dalam 1 database utama dengan kolom `tenant_id` dan *Global Scope Isolation*.

### 🎯 Masalah yang Diselesaikan:
* **Efisiensi Waktu Pengajar**: Memangkas waktu pembuatan puluhan soal ujian dan pembahasan ilmiah dari berjam-jam menjadi **kurang dari 30 detik** menggunakan generator AI otomatis (OpenRouter API).
* **Keamanan & Integritas Ujian**: Mengeliminasi praktik kecurangan daring (*online cheating*) melalui pemantauan perpindahan tab atau jendela browser secara real-time yang didukung oleh perhitungan waktu ujian langsung dari server (*server-authoritative timer*).
* **Kemudahan Deployment**: Menggunakan **Single-Database Tenancy** sehingga dapat di-deploy tanpa memerlukan privilege `CREATE DATABASE` (sangat ramah untuk aaPanel, Shared Hosting cPanel, maupun VPS).

---

## 👥 Fitur Utama & Peran Pengguna (Roles)

Platform ini dibagi menjadi 3 lapisan hak akses (*3-Tier Authentication Guards*) yang memiliki fungsi dan ruang lingkup khusus:

### 👑 1. SuperAdmin (Pusat Platform / Global - Guard `web`)
* **Manajemen Tenant & Lembaga**: Memantau seluruh tenant aktif, statistik penggunaan sistem, dan pengelolaan pemilik tenant (*owner*).
* **Konfigurasi AI / LLM Providers**: Mengatur penyedia model AI (OpenRouter, OpenAI, Custom API) beserta parameter max tokens & temperature.
* **Manajemen Paket Token AI**: Membuat dan menentukan harga paket saldo token AI yang dapat dibeli oleh lembaga.
* **Integrasi Payment Gateway**: Mengonfigurasi gerbang pembayaran otomatis (Midtrans, Xendit, Duitku, Ipaymu, Doku).
* **Audit Logs & System Health**: Memantau log aktivitas sistem & audit trail.

### 🏫 2. Owner / Pengajar (Tenant Lembaga Pendidikan - Guard `owner`)
* **🤖 AI Quiz Generator**: Membuat puluhan soal evaluasi pilihan ganda lengkap dengan bobot poin, kunci jawaban, dan pembahasan ilmiah secara otomatis dari materi pelajaran menggunakan token AI.
* **📝 Interactive Quiz Editor**: Menyunting butir pertanyaan, opsi jawaban, dan pengaturan kuis (durasi, KKM, batas percobaan).
* **👨‍🎓 Manajemen Peserta Ujian**: Mengundang siswa secara individual, mengimpor data siswa via CSV, serta me-reset kata sandi peserta.
* **💰 Top-Up Token AI**: Membeli saldo token AI secara instan melalui integrasi pembayaran Midtrans otomatis atau simulasi sandbox tes.
* **⚙️ Pengaturan Tenant & WhatsApp Gateway**: Menyesuaikan identitas portal (nama lembaga, deskripsi, warna brand) dan integrasi gateway notifikasi.
* **📊 Laporan & Ekspor Analitik**: Melihat rekapitulasi nilai evaluasi, persentase kelulusan siswa, dan mengunduh laporan format CSV/Excel.

### 👨‍🎓 3. Participant / Siswa (Peserta Ujian - Guard `participant`)
* **🏠 Portal Siswa Khusus Tenant**: Mengakses portal ujian lembaga melalui URL path `/{tenant}/login` dengan kredensial undangan yang aman.
* **⏱️ Layar Ujian Anti-Cheat**:
  * **Server-Authoritative Timer**: Waktu ujian berjalan mundur dari server secara akurat dan tidak dapat dimanipulasi client.
  * **Deteksi Tab Switch**: Memantau aktivitas jendela browser. Jika siswa keluar dari halaman ujian, ujian akan **dikumpulkan secara paksa otomatis (*force-submit*)** dan diberi bendera peringatan.
  * **Auto-Save Jawaban**: Setiap pilihan jawaban disimpan secara instan via AJAX.
* **🏆 Riwayat & Pembahasan AI**: Melihat skor akhir, status kelulusan (*Lulus / Belum Lulus*), serta membaca penjelasan ilmiah AI untuk setiap soal sehabis ujian.

---

## 🛠️ Tumpukan Teknologi (Tech Stack) & Library

* **Bahasa Pemrograman**: PHP 8.2+, JavaScript (ES6+ / Vanilla JS), HTML5, CSS3.
* **Framework Utama**: Laravel 12.x.
* **Database & Cache**: MySQL 8.0+ / MariaDB 10.5+ (Single-Database).
* **Library & Paket Kunci**:
  * `stancl/tenancy` (v3.10): Paket path-based URL routing `{tenant}/...`.
  * `spatie/laravel-permission` (v6.25): Manajemen Role & Hak Akses.
  * `laravel/fortify` (v1.37): Layanan backend autentikasi.
  * `livewire/livewire` (v4.3): Komponen UI reaktif.
  * `alpinejs`: Kerangka kerja JS ringan untuk timer & modal UI.

---

## 🗄️ Struktur Database & Arsitektur Single-Database Tenancy

Proyek ini menggunakan **Single-Database Tenancy**, di mana seluruh tabel disimpan dalam 1 database MySQL utama:

### 🌐 1. Tabel Sentral (Central Tables)
* `users`: Kredensial SuperAdmin (`web` guard).
* `owners`: Data Owner Lembaga/Instansi (`owner` guard).
* `tenants` & `domains`: Identitas tenant lembaga, slug, dan status aktif.
* `owner_token_balances` & `token_transactions`: Saldo token AI owner & histori kredit/debit.
* `token_packages`: Katalog paket token AI.
* `token_orders`: Transaksi pembelian token via Payment Gateway.
* `llm_providers`: Konfigurasi provider AI (OpenRouter, API Key, Model).
* `system_settings`: Pengaturan global platform.

### 📁 2. Tabel Tenant (Single-Database dengan `tenant_id` & `TenantScope`)
* `tenant_users`: Data akun siswa/peserta per tenant (`participant` guard).
* `quizzes`: Master kuis evaluasi, passing score, durasi, KKM, prompt AI.
* `questions`: Butir soal kuis & bobot kesulitan.
* `question_options`: Opsi pilihan jawaban & penjelasan ilmiah AI.
* `quiz_participants`: Pivot penugasan kuis private ke peserta.
* `quiz_attempts`: Sesi pengerjaan ujian peserta (authoritative timer, skor, status, end_reason, flags).
* `quiz_answers`: Jawaban peserta per attempt secara real-time.
* `notification_logs`: Log pengiriman notifikasi (Email/WhatsApp).

---

## 💻 Prasyarat Sistem

* **PHP**: Versi >= 8.2 (ekstensi `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `curl`).
* **Database**: MySQL >= 8.0 atau MariaDB >= 10.5.
* **Manajer Paket**: Composer >= 2.x dan Node.js >= 18.x (NPM).

---

## 🚀 Panduan Instalasi Lokal

1. **Kloning repositori proyek:**
   ```bash
   git clone https://github.com/hndko/app_marilms_laravel12.git
   cd app_marilms_laravel12
   ```

2. **Pasang dependensi PHP dan Node.js:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Salin berkas `.env` dan hasilkan kunci aplikasi:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi koneksi database di `.env`:**
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=app_marilms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan migrasi database beserta data awal (seeder):**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan aplikasi:**
   ```bash
   npm run dev
   ```

---

## ⚙️ Setup Queue Worker (`php artisan queue:work`)

Untuk memproses pembuatan kuis AI (LLM) dan notifikasi latar belakang:

```bash
# Menjalankan worker di terminal lokal
php artisan queue:work --sleep=3 --tries=3
```

Panduan setup otomatis menggunakan **Supervisor aaPanel**, **Systemd Service Linux**, maupun **cPanel Cron Job** selengkapnya dapat dibaca di **[Panduan Queue & Deployment](file:///D:/laragon/www/app_marilms_laravel12/docs/DEPLOYMENT.md)**.

---

## 📖 Panduan Deployment

Panduan deployment lengkap untuk berbagai lingkungan dapat diakses pada dokumen terpisah di folder `docs/`:

👉 **[Dokumentasi Deployment Lengkap (`docs/DEPLOYMENT.md`)](file:///D:/laragon/www/app_marilms_laravel12/docs/DEPLOYMENT.md)**

Lingkungan yang didukung:
1. **Local Development** (Laragon / XAMPP)
2. **VPS dengan aaPanel** (Rekomendasi untuk VPS aaPanel)
3. **VPS Linux Murni** (Ubuntu / Debian + Nginx CLI)
4. **Shared Hosting** (cPanel / DirectAdmin)

---

## 🤝 Kontribusi

1. **Fork** repositori ini ke akun GitHub pribadi Anda.
2. Buat *branch* fitur baru: `git checkout -b feature/nama-fitur`.
3. Jalankan pengujian: `php artisan test`.
4. Commit perubahan sesuai *Conventional Commits*: `git commit -m "feat: tambah fitur X"`.
5. Push & buat **Pull Request (PR)**.

---

## ⚖️ Lisensi

Proyek **MariLMS AI** dirilis di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).

---
<p align="center"><b>Dikembangkan dengan ❤️ untuk Kemajuan Evaluasi Pendidikan Digital</b></p>
