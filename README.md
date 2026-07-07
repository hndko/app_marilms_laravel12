# 🎓 MariLMS AI — Portal Evaluasi & Ujian Berbasis AI Multi-Tenant

Platform Learning Management System (LMS) modern berbasis Laravel 12 dengan dukungan arsitektur multi-tenant, pembuatan soal otomatis berbantuan Artificial Intelligence (LLM), serta sistem keamanan ujian anti-cheat terintegrasi.

---

## 📑 Daftar Isi
- [Deskripsi Proyek](#-deskripsi-proyek)
- [Fitur Utama & Peran Pengguna (Roles)](#-fitur-utama--peran-pengguna-roles)
- [Tumpukan Teknologi (Tech Stack) & Library](#-tumpukan-teknologi-tech-stack--library)
- [Struktur Database & Arsitektur Tenancy](#-struktur-database--arsitektur-tenancy)
- [Prasyarat Sistem](#-prasyarat-sistem)
- [Panduan Instalasi](#-panduan-instalasi)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

---

## 💡 Deskripsi Proyek

**MariLMS AI** adalah solusi platform pendidikan digital kelas enterprise yang dirancang khusus untuk sekolah, perguruan tinggi, lembaga bimbingan belajar, maupun institusi pelatihan profesional. Platform ini mengadopsi konsep **Multi-Tenancy** terisolasi di mana setiap lembaga pendidikan memiliki ruang kerja (*workspace*) dan portal ujian tersendiri yang aman dan mandiri.

### 🎯 Masalah yang Diselesaikan:
* **Efisiensi Waktu Pengajar**: Memangkas waktu pembuatan puluhan soal ujian dan pembahasan ilmiah dari berjam-jam menjadi **kurang dari 30 detik** menggunakan generator AI otomatis.
* **Keamanan & Integritas Ujian**: Mengeliminasi praktik kecurangan daring (*online cheating*) melalui pemantauan perpindahan tab atau jendela browser secara real-time yang didukung oleh perhitungan waktu ujian langsung dari server (*server-authoritative timer*).
* **Komunikasi Akademik Real-Time**: Menyederhanakan distribusi informasi akademik kepada siswa melalui integrasi langsung dengan WhatsApp Gateway untuk pengiriman undangan ujian, pengingat, dan hasil evaluasi nilai.

---

## 👥 Fitur Utama & Peran Pengguna (Roles)

Platform ini dibagi menjadi 3 lapisan hak akses (*3-Tier Authentication Guards*) yang memiliki fungsi dan ruang lingkup khusus:

### 👑 1. SuperAdmin (Pusat Platform / Global)
* **Manajemen Tenant & Lembaga**: Memantau seluruh tenant aktif, statistik penggunaan sistem, dan pengelolaan pemilik tenant (*owner*).
* **Konfigurasi AI / LLM Providers**: Mengatur penyedia model AI (OpenRouter, DeepSeek, Custom API) beserta urutan prioritas cadangan (*fallback chain*) agar sistem pembuatan kuis tidak pernah gagal saat terjadi gangguan jaringan.
* **Manajemen Paket Token AI**: Membuat dan menentukan harga paket saldo token AI yang dapat dibeli oleh lembaga.
* **Integrasi Payment Gateway**: Mengonfigurasi gerbang pembayaran otomatis (Midtrans, Xendit, Duitku, Ipaymu, Doku).
* **Audit Logs & System Health**: Memantau log aktivitas sistem, antrean pekerjaan latar belakang (*queue/horizon*), dan parameter keamanan global.

### 🏫 2. Owner / Pengajar (Tenant Lembaga Pendidikan)
* **🤖 AI Quiz Generator**: Membuat puluhan soal evaluasi pilihan ganda lengkap dengan bobot poin, kunci jawaban, dan pembahasan ilmiah secara otomatis dari materi pelajaran menggunakan token AI.
* **📝 Interactive Quiz Editor**: Menyunting butir pertanyaan, opsi jawaban, dan pengaturan kuis (durasi, batas kelulusan, sisa percobaan) melalui antarmuka interaktif.
* **👨‍🎓 Manajemen Peserta Ujian**: Mengundang siswa secara individual, mengimpor ribuan data siswa secara massal menggunakan file CSV, serta me-reset kata sandi peserta.
* **💰 Top-Up Token AI**: Membeli saldo token AI secara instan melalui integrasi pembayaran Midtrans otomatis atau menggunakan fitur simulasi sandbox tes.
* **⚙️ Pengaturan Tenant & WhatsApp Gateway**: Menyesuaikan identitas portal (nama lembaga, deskripsi, warna brand) dan mengintegrasikan WhatsApp Gateway pribadi (Fonnte, Wablast, Log) untuk pengiriman notifikasi otomatis.
* **📊 Laporan & Ekspor Analitik**: Melihat rekapitulasi nilai evaluasi, persentase kelulusan siswa, dan mengunduh laporan ke format CSV/Excel (kompatibel UTF-8 BOM).

### 👨‍🎓 3. Participant / Siswa (Peserta Ujian)
* **🏠 Portal Siswa Khusus Tenant**: Mengakses portal ujian lembaga melalui tautan khusus tenant dengan kredensial undangan yang aman.
* **⏱️ Layar Ujian Anti-Cheat**:
  * **Server-Authoritative Timer**: Waktu ujian berjalan mundur dari server secara akurat dan tidak dapat dimanipulasi melalui konsol browser atau jam sistem komputer.
  * **Deteksi Tab Switch**: Memantau aktivitas jendela browser. Jika siswa keluar dari halaman ujian melebihi batas toleransi, ujian akan **dikumpulkan secara paksa otomatis (*force-submit*)**.
  * **Auto-Save Jawaban**: Setiap pilihan jawaban disimpan secara instan via AJAX sehingga siswa tidak kehilangan progres jika terjadi kendala koneksi sesaat.
* **🏆 Riwayat & Pembahasan AI**: Melihat skor akhir, status kelulusan (*Lulus / Belum Lulus*), serta membaca penjelasan ilmiah AI untuk setiap soal sehabis ujian.

---

## 🛠️ Tumpukan Teknologi (Tech Stack) & Library

* **Bahasa Pemrograman**: PHP 8.2+, JavaScript (ES6+ / Vanilla JS), HTML5, CSS3.
* **Framework Utama**: Laravel 12.x.
* **Database & Cache**: MySQL 8.0+ / MariaDB 10.5+, Redis (untuk *Queue Workers* & *Session Caching*).
* **Library & Paket Kunci**:
  * `stancl/tenancy` (v3.x/v4.x): Paket arsitektur multi-tenancy berbasis jalur (*path-based tenancy* `{tenant}/...`).
  * `livewire/livewire` (v3.x): Komponen UI reaktif dan dinamis untuk interaktivitas layar.
  * `guzzlehttp/guzzle`: Klien HTTP untuk komunikasi dengan penyedia API LLM dan WhatsApp Gateway.
  * `midtrans/midtrans-php`: Integrasi gerbang pembayaran otomatis untuk pembelian token AI.
  * `alpinejs`: Kerangka kerja JavaScript ringan untuk interaktivitas komponen sisi klien dan timer ujian.

---

## 🗄️ Struktur Database & Arsitektur Tenancy

Proyek ini menerapkan model **Path-Based Multi-Tenancy** dengan pemisahan struktur database yang jelas antara data pusat (sentral) dan data lembaga (tenant):

### 🌐 1. Database Sentral (Global Platform)
Database ini menyimpan informasi admin pusat, identitas tenant, konfigurasi LLM, dan transaksi keuangan:
* **`tenants` & `domains`**: Menyimpan identitas unik lembaga, slug subdomain, dan status aktif tenant.
* **`superadmin_users`**: Kredensial dan hak akses pengelola pusat platform.
* **`owners`**: Akun penanggung jawab atau pengajar pemilik lembaga pendidikan.
* **`owner_token_balances` & `token_transactions`**: Menyimpan saldo token AI pengajar beserta riwayat pemakaian dan top-up (diberi perlindungan *row locking* / `lockForUpdate` untuk mencegah *race condition*).
* **`token_packages`**: Master paket token AI yang diperjualbelikan kepada lembaga.
* **`payment_gateways` & `payment_transactions`**: Rekam jejak pesanan dan log verifikasi webhook Midtrans/Xendit.
* **`llm_providers`**: Daftar konfigurasi kunci rahasia API LLM (OpenRouter, DeepSeek, Custom) beserta urutan *fallback chain*.
* **`system_settings`**: Pengaturan global platform dan parameter default sistem.

### 📁 2. Database Tenant (Terisolasi per Lembaga/Sekolah)
Setiap lembaga memiliki ruang isolasi data mandiri sehingga data siswa dan soal ujian antar sekolah tidak akan pernah tercampur:
* **`users` (Participants)**: Data siswa atau peserta ujian yang terdaftar di lembaga tersebut.
* **`quizzes`**: Master paket kuis evaluasi (judul, kategori, durasi waktu, batas kelulusan minimum/KKM, dan metadata AI).
* **`quiz_questions`**: Daftar butir soal, tipe soal, bobot nilai poin, dan teks penjelasan ilmiah AI (*explanation*).
* **`quiz_options`**: Pilihan opsi jawaban per soal (A, B, C, D) dengan penanda status jawaban benar (`is_correct`).
* **`quiz_attempts`**: Rekam jejak sesi pengerjaan ujian siswa (waktu mulai server, durasi total, skor akhir, status pengerjaan, dan alasan selesai seperti `manual`, `time_up`, atau `tab_switch`).
* **`quiz_answers`**: Rekaman jawaban eksplisit yang dipilih siswa pada setiap butir soal secara *real-time*.

---

## 💻 Prasyarat Sistem

Pastikan lingkungan pengembang atau server Anda telah memenuhi spesifikasi minimum berikut:
* **PHP**: Versi >= 8.2 (dengan ekstensi `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `curl`).
* **Database**: MySQL >= 8.0 atau MariaDB >= 10.5.
* **Manajer Paket**: Composer >= 2.x dan Node.js >= 18.x (beserta NPM).
* **Server Caching (Opsional)**: Redis (sangat disarankan untuk pengelolaan antrean latar belakang *Horizon/Queue* di produksi).

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah berbasis terminal berikut untuk memasang dan menjalankan proyek secara lokal:

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

3. **Salin berkas konfigurasi lingkungan dan hasilkan kunci aplikasi:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi koneksi database di dalam berkas `.env`:**
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
php artisan migrate --seed
```

6. **Jalankan server pengembangan lokal dan worker antrean latar belakang:**
```bash
# Terminal 1: Menjalankan web server lokal
php artisan serve

# Terminal 2: Menjalankan queue worker untuk pemrosesan AI & email
php artisan queue:work
```

---

## 📖 Panduan Penggunaan

### 1. Menjalankan Pengujian Otomatis (Automated Testing)
Platform ini dilengkapi dengan *Unit Tests* dan *Feature Tests* untuk memverifikasi logika token, validasi skema AI, dan timer waktu server. Untuk menjalankan seluruh pengujian:
```bash
php artisan test
```

### 2. Contoh Penggunaan `NotificationService` (Kode PHP)
Sistem notifikasi dapat dipanggil secara programatis dari dalam Controller atau Job untuk mengirim pesan WhatsApp dan Email secara simultan:
```php
use App\Services\NotificationService;

$notifService = new NotificationService();

// Mengirim undangan kredensial login ke WhatsApp dan Email peserta
$notifService->send('participant_invited', $participant, [
    'email' => $participant->email,
    'password' => 'rahasia123',
    'url' => url('/tenant-sekolah/login')
], sendWa: true, sendEmail: true);
```

### 3. Contoh Penggunaan `LlmService` untuk Pembuatan Soal AI (Kode PHP)
Memanggil layanan LLM dengan dukungan *fallback chain* otomatis:
```php
use App\Services\LlmService;

$llmService = new LlmService();

// Men-generate soal berdasarkan prompt yang telah dibentuk oleh PromptBuilder
$jsonResponse = $llmService->generateQuestion($promptText);
$quizData = json_decode($jsonResponse, true);
```

### 4. Menjalankan Perintah Maintenance & Optimasi Produksi
Saat aplikasi hendak dirilis ke lingkungan produksi, bersihkan dan buat cache baru agar kinerja maksimal:
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🤝 Kontribusi

Kami sangat menyambut kontribusi dari pengembang luar untuk memperkaya fitur dan meningkatkan kinerja platform **MariLMS AI**. Berikut adalah aturan main untuk berkontribusi:

1. **Fork** repositori ini ke akun GitHub pribadi Anda.
2. Buat *branch* fitur baru dari branch `main`:
   ```bash
   git checkout -b feature/nama-fitur-kreatif
   ```
3. Lakukan perubahan kode dan pastikan standar penulisan rapi serta mempertahankan komentar/dokumentasi yang ada.
4. **Wajib Menjalankan Test**: Pastikan seluruh pengujian otomatis lulus (*100% green tests*) sebelum melakukan commit:
   ```bash
   php artisan test
   ```
5. Lakukan commit dengan pesan yang jelas menggunakan konvensi standar (contoh: `feat: add export to pdf feature` atau `fix: resolve timer sync issue`):
   ```bash
   git commit -m "feat: deskripsi fitur baru Anda"
   ```
6. Push *branch* tersebut ke repositori fork Anda:
   ```bash
   git push origin feature/nama-fitur-kreatif
   ```
7. Buat **Pull Request (PR)** menuju branch `main` di repositori utama beserta deskripsi lengkap mengenai perubahan yang Anda buat.

---

## ⚖️ Lisensi

Proyek **MariLMS AI** dirilis dan didistribusikan di bawah [Lisensi MIT](https://opensource.org/licenses/MIT). Anda bebas untuk menggunakan, memodifikasi, dan mendistribusikan perangkat lunak ini baik untuk keperluan komersial maupun non-komersial sesuai dengan syarat dan ketentuan yang berlaku dalam lisensi tersebut.

---
<p align="center"><b>Dikembangkan dengan ❤️ untuk Kemajuan Evaluasi Pendidikan Digital</b></p>
