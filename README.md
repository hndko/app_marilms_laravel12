# 🎓 MariLMS AI — Platform LMS & Portal Ujian Multi-Tenant Berbasis AI (v1.4.2)

**MariLMS AI** adalah platform *Learning Management System* (LMS) dan evaluasi ujian digital multi-tenant berbasis Laravel 12 dengan fitur pembuatan kuis otomatis berbantuan Artificial Intelligence serta sistem pengawasan ujian anti-kecurangan terintegrasi.

---

## 📑 Daftar Isi
* [💡 Deskripsi Proyek](#-deskripsi-proyek)
* [💻 Prasyarat](#-prasyarat)
* [🚀 Instalasi](#-instalasi)
* [⚙️ Penggunaan](#-penggunaan)
* [👥 Peran Pengguna (Roles) & Fitur](#-peran-pengguna-roles--fitur)
* [🛠️ Tumpukan Teknologi & Library](#-tumpukan-teknologi--library)
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

# Terminal 2: Vite Frontend Dev Server
npm run dev

# Terminal 3: Pemroses Antrean Kuis AI & Notifikasi
php artisan queue:work --sleep=3 --tries=3
```

### 2. Contoh Potongan Kode Logika Utama

#### A. Isolasi Data Tenant (`TenantScope`)
Seluruh model data milik tenant diisolasi secara otomatis menggunakan `TenantScope` dan pengisian `tenant_id`:

```php
namespace App\Models\Tenant;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
        
        static::creating(function ($model) {
            if (tenant()) {
                $model->tenant_id = tenant('id');
            }
        });
    }
}
```

#### B. Perhitungan Sisa Waktu Ujian Otoritatif Server
Menghitung sisa waktu pengerjaan kuis peserta secara presisi dari server untuk mencegah manipulasi client:

```php
public function getRemainingSeconds(): int
{
    $elapsed = $this->started_at->diffInSeconds(now());
    $totalDuration = $this->quiz->duration_minutes * 60;
    
    return max(0, $totalDuration - $elapsed);
}
```

#### C. Pengujian Sintaks dan Pengujian Aplikasi
```bash
# Uji sintaks file PHP
php -l app/Http/Controllers/Auth/AuthController.php

# Jalankan pengujian otomatis (Unit/Feature Tests)
php artisan test
```

---

## 👥 Peran Pengguna (Roles) & Fitur

Aplikasi ini mengimplementasikan *3-Tier Authentication Guards* untuk memisahkan wewenang pengguna secara ketat:

### 👑 1. SuperAdmin (Pusat Platform / Guard `web`)
* **Manajemen Tenant & Owner**: Kelola pendaftaran lembaga/tenant, pemblokiran, dan penambahan saldo token secara manual.
* **Konfigurasi AI / LLM Provider**: Pengaturan penyedia model AI (OpenRouter, OpenAI) beserta *API Key*, model utama, dan *fallback chain*.
* **Katalog Paket Token & Payment Gateway**: Konfigurasi harga paket token AI dan integrasi payment gateway (Midtrans, Xendit, Doku, Duitku, Ipaymu).
* **Audit & Gateway Notifikasi**: Pemantauan log aktivitas sistem serta integrasi WhatsApp/Email Gateway.

### 🏫 2. Owner / Pengajar (Tenant Lembaga / Guard `owner`)
* **🤖 AI Quiz Generator**: Membuat puluhan soal pilihan ganda, kunci jawaban, dan pembahasan ilmiah berbasis AI secara otomatis dari materi/prompt.
* **📝 Interactive Quiz Editor**: Kelola butir soal, bobot nilai, KKM, durasi ujian, dan jumlah batas percobaan (*retry limit*).
* **👨‍🎓 Manajemen Peserta**: Undang peserta, impor data siswa via berkas CSV, dan reset kata sandi peserta.
* **💰 Top-Up Token AI**: Pembelian saldo token AI secara otomatis melalui integrasi Payment Gateway.
* **📊 Laporan & Ekspor Analitik**: Rekapitulasi hasil ujian, grafik persentase kelulusan, dan ekspor laporan format CSV/Excel.

### 👨‍🎓 3. Participant / Siswa (Peserta Ujian / Guard `participant`)
* **🏠 Portal Ujian Tenant**: Akses halaman kuis melalui URL khusus lembaga (`/{tenant}/login`).
* **⏱️ Layar Ujian Anti-Cheat**: 
  * Waktu ujian berjalan mundur terpusat dari server (*server-authoritative timer*).
  * Pemantauan *tab switch* dan penutupan browser yang memicu pengumpulan otomatis (*force-submit*) dan penandaan bintik kecurangan (*is_flagged*).
  * Penyimpanan jawaban otomatis (*auto-save*) berbasis AJAX.
* **🏆 Hasil & Pembahasan AI**: Melihat skor akhir, status kelulusan, serta membaca pembahasan jawaban otomatis pasca ujian.

---

## 🛠️ Tumpukan Teknologi & Library

* **Bahasa Pemrograman**: PHP 8.2+, JavaScript (ES6+), HTML5, CSS3.
* **Backend Framework**: Laravel 12.x (`laravel/framework ^12.0`).
* **Autentikasi & Otorisasi**: Laravel Fortify (`laravel/fortify ^1.37`), Spatie Permission (`spatie/laravel-permission ^6.25`).
* **Multi-Tenancy**: Stancl Tenancy (`stancl/tenancy ^3.10`) dengan penyesuaian *Single-Database Tenancy*.
* **Frontend & Styling**: Livewire (`livewire/livewire ^4.3`), Tailwind CSS v4 (`@tailwindcss/vite`), Alpine.js (`alpinejs ^3.15`), Vite 7 (`vite ^7.0`).
* **Integrasi AI & Gateway**: OpenRouter API / OpenAI Client, Midtrans Payment Gateway, WhatsApp Gateway (Fonnte/Wablast).

---

## 🗄️ Struktur Database

Sistem mengadopsi **Single-Database Tenancy**, di mana seluruh tabel tersimpan dalam 1 database terpusat:

### 🌐 1. Tabel Sentral (Central Tables)
* `users`: Kredensial akun SuperAdmin global.
* `owners`: Data Pemilik Instansi/Lembaga.
* `tenants` & `domains`: Identitas tenant lembaga, slug URL, dan status akses.
* `owner_token_balances` & `token_transactions`: Saldo token AI milik owner dan riwayat transaksi kredit/debit.
* `token_packages` & `token_orders`: Katalog paket token AI dan riwayat pemesanan via Payment Gateway.
* `llm_providers`: Konfigurasi provider AI, model, dan API Key.
* `gateway_configs`: Konfigurasi gateway pembayaran, email, dan WhatsApp.
* `system_settings` & `activity_logs`: Pengaturan global aplikasi dan log jejak audit.

### 📁 2. Tabel Tenant (Berisi Kolom `tenant_id` & Global Scope `TenantScope`)
* `tenant_users`: Akun peserta/siswa per tenant lembaga.
* `quizzes`: Master data kuis, durasi, KKM, passing score, dan prompt AI.
* `questions`: Butir soal kuis, tingkat kesulitan, dan urutan tampilan.
* `question_options`: Pilihan jawaban kuis, kunci jawaban (`is_correct`), dan penjelasan ilmiah AI.
* `quiz_participants`: Penugasan kuis secara privat ke peserta tertentu.
* `quiz_attempts`: Sesi pengerjaan ujian peserta (authoritative timer, skor, status, *end_reason*, dan *flag* kecurangan).
* `quiz_answers`: Jawaban yang dipilih peserta per sesi attempt secara real-time.
* `notification_logs`: Log riwayat pengiriman notifikasi Email & WhatsApp.

---

## 🤝 Kontribusi

Kami menyambut baik kontribusi dari komunitas pengembang! Untuk berkontribusi pada kode MariLMS AI:

1. **Fork** repositori ini ke akun GitHub Anda.
2. Buat *branch* fitur atau perbaikan baru:
   ```bash
   git checkout -b feat/nama-fitur-baru
   ```
3. Pastikan kode memenuhi aturan sintaks dan pengujian:
   ```bash
   php artisan test
   ```
4. Commit perubahan Anda mengikuti standar **Conventional Commits**:
   ```bash
   git commit -m "feat(quiz): tambah fitur pendeteksi tab switch otomatis"
   ```
5. Push ke *branch* Anda dan buat **Pull Request (PR)** dengan penjelasan perubahan yang jelas.

---

## ⚖️ Lisensi

Proyek **MariLMS AI** didistribusikan di bawah naungan [Lisensi MIT](https://opensource.org/licenses/MIT). Anda bebas menggunakan, memodifikasi, dan mendistribusikan perangkat lunak ini sesuai ketentuan lisensi.

---
<p align="center"><b>MariLMS AI — Solusi Evaluasi & Ujian Digital Cerdas Terintegrasi ❤️</b></p>
