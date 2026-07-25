# 🤖 GEMINI.md - Panduan & Aturan Proyek untuk AI Assistant

Dokumen ini berisi panduan teknis, aturan pengodean (*coding standards*), arsitektur sistem, dan instruksi wajib yang harus diikuti oleh **Gemini / Antigravity AI Assistant** dalam setiap pengembangan, perbaikan bug, maupun refactoring pada proyek ini.

---

## 🏢 1. Ringkasan Proyek

* **Nama Proyek:** MariLMS — AI-Powered Multi-Tenant Learning Management System (Single-Database Tenancy)
* **Deskripsi:** Platform LMS multi-tenant cerdas berbasis AI untuk pembuatan kuis otomatis (via LLM OpenRouter/OpenAI), pengelolaan peserta, pelaksanaan ujian real-time dengan pengawasan tab-switch & timer ketat, serta monetisasi berbasis sistem token & Payment Gateway.
* **Tujuan:** Efisiensi pembuatan kuis dengan AI, isolasi data multi-tenant berbasis single-database (`tenant_id` + `TenantScope`), pengawasan ujian anti-kecurangan, pengelolaan token owner, dan transaksi pembayaran otomatis.

---

## 🛠️ 2. Stack Teknologi & Prasyarat

| Komponen | Teknologi / Library | Versi / Keterangan |
| :--- | :--- | :--- |
| **Language** | PHP, JavaScript (ES6+), HTML5, CSS3 | PHP `>= 8.2`, Node `>= 18.0` |
| **Backend Framework** | [Laravel 12](https://laravel.com) | `laravel/framework ^12.0` |
| **Autentikasi & Authorization** | Laravel Fortify & Spatie Permission | Fortify `^1.37`, Spatie `^6.25` |
| **Multi-Tenancy** | Stancl Tenancy (Single-DB) + Custom `TenantScope` | `stancl/tenancy ^3.10` (Path-based routing `/{tenant}/`) |
| **Frontend & Components** | Livewire & Blade Templates (Tailwind CSS v4) | `livewire/livewire ^4.3`, Vite |
| **Styling & CSS** | Tailwind CSS v4 & Vite Plugin | `@tailwindcss/vite` |
| **AI Integration** | OpenRouter API / OpenAI API Client | Dynamic LLM Provider via Service Layer |
| **Payment Gateways** | Midtrans, Xendit, Ipaymu, Doku, Duitku | Webhook verification & simulation support |
| **Database** | MySQL / MariaDB | Single Database (Central + Tenant tables) |

---

## 🗄️ 3. Arsitektur Database & Tabel Utama

### A. Central Tables (Tabel Pengelolaan Utama)
1. `users`: Akun SuperAdmin pengelola platform MariLMS (guard `web`).
2. `owners`: Data Owner Lembaga/Instansi penyedia kuis (guard `owner`).
3. `tenants`: Data tenant lembaga (`id`, `slug`, `name`, `owner_id`, `is_active`).
4. `domains`: Domain/subdomain tenant.
5. `owner_token_balances`: Stok saldo token owner untuk generate kuis AI.
6. `token_transactions`: Histori riwayat kredit/debit token.
7. `token_packages`: Katalog paket token yang dijual ke Owner.
8. `token_orders`: Transaksi order pembelian token via Payment Gateway.
9. `llm_providers`: Konfigurasi provider AI (OpenRouter, API Key, Model, Max Tokens).
10. `gateway_configs_tables` (`payment_gateway_configs`, `email_gateway_configs`, `whatsapp_gateway_configs`): Konfigurasi gateway eksternal.
11. `system_settings_and_activity_logs_tables`: Pengaturan sistem global & log aktivitas.
12. `roles`, `permissions`, `model_has_roles`, `role_has_permissions`: Spatie Role & Permission management.

### B. Tenant Tables (Single-Database, Berisi Kolom `tenant_id`)
13. `tenant_users`: Akun Peserta/Participant kuis per tenant (guard `participant`).
14. `tenant_password_reset_tokens`: Reset token password peserta.
15. `quizzes`: Data kuis, passing score, retry limit, durasi, prompt AI.
16. `questions`: Soal-soal kuis, tingkat kesulitan, order.
17. `question_options`: Pilihan jawaban kuis, indikator `is_correct`, pembahasan/penjelasan.
18. `quiz_participants`: Penugasan kuis private ke peserta tertentu.
19. `quiz_attempts`: Sesi pengerjaan kuis peserta (authoritative timer server, score, status, end_reason, `is_flagged`).
20. `quiz_answers`: Jawaban yang dipilih peserta per attempt kuis.
21. `notification_logs`: Riwayat log pengiriman notifikasi (Email/WhatsApp).

---

## 👥 4. Daftar Peran (Roles) & Guard

| Guard | Kode / Peran (Role) | Hak Akses Utama |
| :--- | :--- | :--- |
| `web` | **Superadmin** | Pengelola utama platform MariLMS (manage owners, token packages, system settings, LLM providers, & gateway settings). |
| `owner` | **Owner** | Pemilik lembaga/tenant (generate kuis AI, kelola peserta, kelola kuis, monitor laporan hasil ujian, beli token). |
| `participant` | **Participant** | Peserta ujian di bawah tenant tertentu (login di `/{tenant}/login`, kerjakan kuis, lihat hasil & riwayat skor). |

---

## 🚨 5. Aturan Wajib untuk AI Assistant (Gemini Guidelines)

### A. Arsitektur Single-Database Tenancy (Strict Mandatory)
* **Dilarang Mengaktifkan `DatabaseTenancyBootstrapper`:** Seluruh tabel tenant wajib disimpan di database central yang sama dengan menggunakan kolom `tenant_id`. Dilarang membuat database MySQL terpisah saat membuat tenant baru.
* **Wajib Gunakan `TenantScope`:** Setiap Eloquent Model tenant (`User`, `Quiz`, `Question`, `QuestionOption`, `QuizParticipant`, `QuizAttempt`, `QuizAnswer`, `NotificationLog`) WAJIB mendaftarkan `TenantScope` sebagai Global Scope dan mengisi `tenant_id` secara otomatis saat `creating`.
* **Path-Based Routing Scope:** Semua route tenant/participant WAJIB berada di dalam group `Route::middleware([InitializeTenancyByPath::class])->prefix('/{tenant}')`.

### B. Keamanan, Autentikasi & Guard Scoping
* **Strict Guard Division:** Pastikan auth check menggunakan guard yang tepat (`auth:web` untuk SuperAdmin, `auth:owner` untuk Owner, `auth:participant` untuk Peserta).
* **Mass Assignment Protection:** JANGAN PERNAH menggunakan `$request->all()` saat `Model::create()` atau `Model::update()`. Selalu gunakan `$request->validated()` atau `$request->only(...)`.
* **Rate Limiting:** Terapkan `middleware('throttle:5,1')` pada endpoint autentikasi publik (`/login`, `/register`).

### C. Timer Otoritatif & Anti-Kecurangan (Quiz Engine)
* **Authoritative Server Timer:** Sisa waktu ujian WAJIB dihitung secara otoritatis di server (`started_at` + `total_duration_seconds` - `now()`). Client TIDAK BOLEH mengontrol atau memanipulasi waktu ujian.
* **Tab Switch & Anti Cheat Detection:** Jika peserta berpindah tab atau menutup browser, catat `end_reason` (`tab_switch` / `browser_close`) dan set `is_flagged = true`.

### D. Konsumsi Token & AI Generation
* **Atomic Token Deduction:** Pemotongan token saat generate kuis AI WAJIB dibungkus dalam `DB::transaction()` dan mengecek ketersediaan saldo di `OwnerTokenBalance` terlebih dahulu.

### E. Standar Tampilan UI & Form (Mandatory UI Rules)
* **Icon Group & Placeholder pada Input Form:** Setiap elemen input form (text, email, password, select, search, date) WAJIB memiliki Icon Group (ikon SVG di dalam/di samping input) dan teks placeholder yang informatif.
* **Ikon pada Tombol (Buttons):** Setiap tombol aksi WAJIB memiliki ikon SVG. Khusus untuk tombol di dalam kolom aksi tabel (*action column*), WAJIB hanya menampilkan ikon saja (*icon-only button*) dengan tooltip/title yang jelas.
* **Information Card Modul (Dengan Show/Hide Toggle):** Setiap berkas tampilan modul/halaman utama aplikasi (khusus modul internal/dashboard, tidak termasuk halaman autentikasi publik) WAJIB menyediakan **Information Card** yang dapat disembunyikan/dikeluarkan (*collapsible*) menggunakan Alpine.js (`x-data="{ showInfoCard: true }"`). Card ini menjelaskan secara transparan:
  1. **Fungsi & Tujuan Modul:** Fitur ini digunakan untuk apa.
  2. **Panduan Tombol:** Fungsi dari setiap tombol yang ada pada modul.
  3. **Logika Bisnis:** Cara kerja alur data, validasi, dan keamanan di balik layar.

### F. Standar Autentikasi Terpadu Central (Unified Central Login)
* **Single Unified Login Page:** Halaman login central (`/login`) HANYA ada SATU untuk seluruh peran di tingkat Central (SuperAdmin & Owner). Dilarang memisah halaman login per peran (seperti `/superadmin/login` atau `/owner/login`) dan dilarang meminta input/pilihan role pada form login.
* **Autodeteksi Peran (Role Auto-Detection):** Controller login (`LoginController`) secara otomatis mencoba autentikasi ke guard `web` (SuperAdmin) terlebih dahulu, kemudian ke guard `owner` (Owner). Pengguna akan langsung diarahkan ke dashboard masing-masing (`/superadmin/dashboard` atau `/{tenant}/dashboard`) secara transparan tanpa perlu memilih role.

---

## 💻 6. Perintah Verifikasi Standar

Sebelum menyelesaikan tugas pengodean, AI Assistant WAJIB mengeksekusi perintah verifikasi berikut:

```sh
# 1. Cek sintaks PHP pada file yang diubah
php -l <filepath>

# 2. Cek pendaftaran rute
php artisan route:list

# 3. Jalankan migrasi & seeder (jika ada perubahan skema)
php artisan migrate:fresh --seed

# 4. Clear cache konfigurasi & rute jika diperlukan
php artisan config:clear
php artisan route:clear
```

---

## 📐 7. Format Respon API Standardized

Seluruh JSON response API dari Controller (jika ada API endpoint) harus mengikuti struktur berikut:

```json
// Respon Sukses Single/Store/Update
{
  "message": "Pesan sukses aksi",
  "data": { ... }
}

// Respon Sukses Paginasi List
{
  "current_page": 1,
  "data": [ { ... } ],
  "total": 100,
  "per_page": 15
}

// Respon Error Validasi (422)
{
  "errors": {
    "field_name": [ "Pesan error validasi" ]
  }
}

// Respon Error Otorisasi / Konflik (403 / 409 / 500)
{
  "message": "Deskripsi pesan kesalahan yang jelas."
}
```

---

## 🏷️ 8. Aturan Versioning Aplikasi (Semantic Versioning - SemVer)

Setiap pengerjaan peningkatan kode, perbaikan bug, atau penambahan fitur baru WAJIB menaikkan penomoran versi aplikasi (`MAJOR.MINOR.PATCH`) di file `package.json`, `composer.json`, `README.md`, dan `GEMINI.md`:

* **MAJOR (`X.0.0`):** Naik saat ada perubahan besar yang tidak kompatibel dengan versi sebelumnya (*breaking changes*).
* **MINOR (`1.X.0`):** Naik saat ada penambahan fitur baru yang aman dan kompatibel dengan versi sebelumnya.
* **PATCH (`1.0.X`):** Naik saat ada perbaikan bug (*bugfixes*), refactoring, atau perbaikan kecil yang aman.

---

## 📦 9. Aturan Git Commit & Push Otomatis (Conventional Commits)

Setiap selesai pengerjaan tugas, AI Assistant WAJIB melakukan commit dan push ke GitHub secara otomatis dengan aturan:

1. **Format Pesan Commit (Conventional Commits):**
   * Format: `<type>(<scope>): <description>`
   * **Tipe (`type`):**
     * `feat`: Menambahkan fitur baru.
     * `fix`: Memperbaiki bug atau error.
     * `docs`: Mengubah atau memperbarui dokumentasi.
     * `style`: Mengubah format kode tanpa mengubah logika (spasi, titik koma, kerapian).
     * `refactor`: Mengubah struktur kode tanpa menambah fitur / memperbarui bug.
     * `test`: Menambah atau memperbaiki unit test.
     * `chore`: Perawatan rutin (update dependency, konfigurasikan file).
2. **Aturan Penulisan Kalimat Commit:**
   * **Kata Kerja Imperatif (Perintah):** Gunakan `add`, `fix`, `update`, `refactor` (bukan `added`, `fixing`, `updated`).
   * **Judul Maksimal 50 Karakter:** Jaga judul subjek singkat dan jelas.
   * **Tanpa Tanda Titik:** Dilarang mengakhiri baris subjek dengan tanda titik `.`.
   * **Commit Atomik:** 1 commit fokus pada 1 tugas spesifik dan pastikan kode tidak broken sebelum commit.

---

## 🔄 10. Kewajiban Pengkinian Dokumen (Mandatory Sync)

* Setiap kali ada aturan baru atau penyesuaian panduan pengodean, WAJIB langsung dicatat dan diperbarui pada `GEMINI.md`.
* Setiap kali ada penambahan fitur baru, perbaikan besar, perubahan API, atau kenaikan versi, WAJIB langsung memperbarui `README.md`.
