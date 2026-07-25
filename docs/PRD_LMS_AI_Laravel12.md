# Product Requirements Document (PRD)
## AI-Powered LMS (Learning Management System)
### Versi: 1.1 | Stack: Laravel 12 + Blade/Livewire + Multi-Tenant (tenancyforlaravel.com)

---

## 1. RINGKASAN PRODUK

### 1.1 Visi Produk
Membangun platform Learning Management System (LMS) berbasis multi-tenant yang dilengkapi kemampuan pembuatan soal kuis secara otomatis menggunakan AI (LLM). Setiap organisasi/institusi (owner) memiliki tenant terisolasi, bisa membuat soal AI, mengelola peserta, dan mengatur pembayaran token untuk penggunaan fitur AI.

### 1.2 Tech Stack
| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel 12 |
| Multi-Tenancy | stancl/tenancy (tenancyforlaravel.com) |
| Database Utama | MySQL / PostgreSQL |
| Cache & Queue | Redis |
| Frontend | Blade + Livewire 3 |
| Real-time / Timer Sync | Livewire polling + Alpine.js countdown |
| LLM Provider | OpenRouter (default), DeepSeek, Custom (base_url + API key + model) |
| Payment Gateway | Midtrans, Xendit, iPaymu, DOKU, Duitku |
| WhatsApp Gateway | Fonnte, Wablast |
| Email | SMTP / Mailgun / konfigurasi custom |
| Auth | Laravel Sanctum / Fortify |
| Storage | Local / S3-compatible |

### 1.3 Arsitektur Multi-Tenant
- **Central Database**: menyimpan data SuperAdmin, daftar Owner, konfigurasi global, token, pembayaran.
- **Tenant Database**: per Owner — menyimpan data pengguna tenant, soal kuis, hasil ujian, pengaturan lokal.
- **URL Pattern (Path-based)**: `domain.com/{slug-owner}/` — tidak membutuhkan wildcard subdomain DNS.
  - SuperAdmin panel: `domain.com/superadmin/`
  - Owner panel: `domain.com/{slug}/dashboard`
  - Peserta quiz: `domain.com/{slug}/quiz/{id}`
- stancl/tenancy dikonfigurasi menggunakan **path identification** bukan subdomain.

---

## 2. PERAN & HIERARKI PENGGUNA

### 2.1 SuperAdmin
- Satu akun global, tidak terikat tenant.
- Mengelola seluruh sistem dari panel terpusat.

### 2.2 Owner
- Mendaftar dan memiliki satu tenant masing-masing.
- Mengelola kuis, pengguna, dan setting di dalam tenantnya.
- Memiliki kuota token AI; bisa bertipe **reguler** atau **unlimited** (diatur SuperAdmin).

### 2.3 User (Peserta / Participant)
- Dibuat atau diundang oleh Owner di dalam tenant.
- Mengerjakan kuis dan melihat hasil nilai secara langsung.

---

## 3. MODUL SUPERADMIN

### 3.1 Dashboard SuperAdmin
- Total Owner aktif / nonaktif
- Total token terjual & dikonsumsi (grafik)
- Pendapatan dari payment gateway (ringkasan per gateway)
- Log aktivitas sistem terbaru

### 3.2 Manajemen Owner (User Management)

#### Data Owner
| Field | Keterangan |
|---|---|
| Nama Lengkap | - |
| Email | Unik, digunakan login |
| Nama Tenant / Organisasi | - |
| Slug Tenant | Otomatis dari nama, bisa diedit |
| Status | Aktif / Nonaktif |
| Tipe Owner | `regular` / `unlimited_token` |
| Token Saldo | Saldo token saat ini |
| Tanggal Registrasi | - |

#### Fitur di User Management
- **CRUD Owner**: tambah, edit, hapus/nonaktifkan.
- **Toggle Unlimited Token**: SuperAdmin bisa menandai Owner tertentu sebagai `unlimited_token` → Owner ini bebas generate soal tanpa batas tanpa pengurangan token.
- **Atur Token Awal**: Berapa token gratis yang diberikan saat Owner baru mendaftar (default: 50, bisa diubah kapan saja — berlaku untuk registrasi berikutnya).
- **Top-up Token Manual**: SuperAdmin bisa menambah token ke Owner tertentu secara manual.
- **Reset Password Owner**
- **Login As Owner** (impersonation) untuk keperluan support

### 3.3 Konfigurasi Token & Timer Global
| Setting | Default | Keterangan |
|---|---|---|
| `free_token_on_register` | 50 | Token gratis saat Owner baru daftar |
| `token_per_question` | 1 | Token yang dikonsumsi per soal yang di-generate |
| `token_package_*` | Bervariasi | Paket token yang dijual (bisa multi-paket) |
| `default_seconds_per_question` | 60 | Detik per soal jika kuis tidak punya durasi custom |
| `tab_switch_action` | `end_quiz` | Aksi saat pindah tab: `end_quiz` (default) |

### 3.4 Paket Token (Token Package)
SuperAdmin bisa membuat paket token untuk dijual ke Owner:
- Nama Paket (e.g., "Starter 100 Token", "Pro 500 Token")
- Jumlah Token
- Harga (IDR)
- Status Aktif/Nonaktif
- Paket bisa diatur per payment gateway yang tersedia

### 3.5 Konfigurasi LLM Provider (Global Default)
SuperAdmin mengatur LLM provider. **Default pertama adalah OpenRouter** (sudah dikonfigurasi saat instalasi).

| Field | Keterangan |
|---|---|
| Provider | `openrouter` *(default)* / `deepseek` / `custom` |
| Base URL | Endpoint API — OpenRouter: `https://openrouter.ai/api/v1` |
| API Key | Kunci API |
| Model | Nama model — contoh OpenRouter: `openai/gpt-4o-mini`, `anthropic/claude-3-haiku`, `google/gemini-flash-1.5` |
| Max Tokens per Request | Batas token LLM output per generate (default: 4000) |
| Temperature | 0.0 – 1.0 (default: 0.7) |
| Prioritas | Integer — urutan provider dicoba (1 = utama) |
| Status | Aktif / Fallback / Nonaktif |

- Bisa menambah beberapa provider; jika provider utama gagal/timeout → fallback ke provider berikutnya secara otomatis.
- **OpenRouter dipilih sebagai default** karena mendukung ratusan model dalam satu API key (model bisa diganti tanpa ubah konfigurasi lain).
- Owner juga bisa punya konfigurasi LLM sendiri (override global) — opsional, jika SuperAdmin mengizinkan.

### 3.6 Email Gateway
Konfigurasi email system-wide:
- Driver: SMTP, Mailgun, Postmark, SES, dll.
- Host, Port, Username, Password, Encryption
- From Name & From Email
- **Test Kirim Email** dari panel

### 3.7 WhatsApp Gateway
Pilihan provider WhatsApp yang dipakai sistem:

#### Fonnte
- API Key
- Nomor pengirim (device)
- Test kirim pesan

#### Wablast
- API Key / Token
- Nomor pengirim
- Test kirim pesan

Setting: SuperAdmin bisa pilih provider aktif (Fonnte atau Wablast) atau keduanya dengan fallback.

Owner juga bisa mengatur WhatsApp Gateway sendiri di dalam tenant mereka (override global, jika SuperAdmin mengizinkan).

### 3.8 Payment Gateway

SuperAdmin mengaktifkan/menonaktifkan dan mengonfigurasi setiap gateway:

#### Midtrans
- Server Key, Client Key
- Mode: Sandbox / Production
- Webhook URL (auto-generated)

#### Xendit
- Secret API Key, Public API Key
- Mode: Test / Live
- Callback URL

#### iPaymu
- Virtual Account, API Key
- Mode: Sandbox / Production

#### DOKU
- Merchant Code, Secret Key
- Mode: Sandbox / Production

#### Duitku
- Merchant Code, API Key
- Mode: Sandbox / Production

Setiap gateway: bisa diaktifkan/dinonaktifkan secara independen. Antarmuka dirancang mudah diperluas (extendable) untuk gateway baru di masa mendatang melalui interface/driver pattern.

### 3.9 Pengaturan Sistem Umum
- Nama Aplikasi
- Logo & Favicon
- Bahasa Default
- Timezone
- Maintenance Mode
- Kebijakan Privasi & Syarat & Ketentuan (editor teks)
- Landing page on/off

### 3.10 Log & Audit
- Log aktivitas SuperAdmin
- Log generate soal AI (Owner, tanggal, token dikonsumsi, status)
- Log transaksi token (pembelian, manual top-up, konsumsi)
- Log pengiriman notifikasi (email & WA)

---

## 4. MODUL OWNER (TENANT)

Owner mengakses panel mereka melalui `domain.com/{slug}/dashboard` (path-based, tidak perlu subdomain).

### 4.1 Dashboard Owner
- Jumlah soal aktif
- Jumlah peserta terdaftar
- Total pengerjaan kuis (hari ini / bulan ini)
- Sisa token (dengan indikator warna: hijau/kuning/merah)
- Tombol cepat: Buat Soal, Undang Peserta, Beli Token

### 4.2 Token & Pembelian Token

#### Tampilan Saldo Token
- Saldo saat ini
- Riwayat penggunaan token (tanggal, kuis, jumlah dikonsumsi)
- Riwayat pembelian token

#### Beli Token
- Tampilkan daftar paket token yang tersedia (dari SuperAdmin)
- Owner pilih paket → pilih payment gateway aktif
- Redirect ke halaman pembayaran gateway
- Setelah sukses → token otomatis ditambahkan
- Notifikasi via email & WA (jika dikonfigurasi)

**Catatan**: Owner dengan flag `unlimited_token` tidak melihat saldo token dan tidak terkena deduction — tetap bisa generate soal bebas.

### 4.3 Manajemen Kuis (Quiz Management)

#### 4.3.1 Pembuatan Soal via AI

Form input generate soal:

| Field | Tipe | Keterangan |
|---|---|---|
| Topik / Perintah | Textarea | "Buat soal tentang fotosintesis untuk SMA" |
| Kategori Soal | Dropdown / Input | e.g., Biologi, Matematika, Pemrograman |
| Tingkat Kesulitan | Radio | Mudah / Sedang / Sulit |
| Jumlah Soal | Number | Minimal 1 |
| Tipe Soal | Checkbox | Pilihan Ganda (wajib, v1), Essay (roadmap) |
| Minimal Nilai Lulus | Number | 0–100 (e.g., 70) |
| Jumlah Opsi Jawaban | Radio | 3 / 4 / 5 opsi |
| **Durasi Ujian** | Number (menit) / Tidak Ada | Jika diisi → total timer = N menit. Jika kosong → timer otomatis = jumlah soal × `default_seconds_per_question` |
| Deadline | Date-Time / None | Opsional — batas tanggal pengerjaan |
| Status | Toggle | Aktif / Nonaktif |
| Batas Percobaan Ulang | Number / Unlimited | Berapa kali boleh retry (0 = unlimited) |
| Akses Peserta | Radio | Publik (semua peserta tenant) / Pilih Manual |
| Peserta Terpilih | Multi-select | Jika akses = manual |

**Proses Generate:**
1. Validasi saldo token (jumlah soal × token_per_question).
2. Kirim prompt ke LLM provider.
3. LLM mengembalikan JSON array soal.
4. Soal disimpan ke database tenant.
5. Token dikurangi dari saldo Owner.
6. Owner diarahkan ke halaman preview & edit soal.

**Format JSON dari LLM (contoh):**
```json
[
  {
    "question": "Apa fungsi utama klorofil dalam fotosintesis?",
    "options": [
      "Menyerap cahaya matahari",
      "Menghasilkan oksigen",
      "Memecah air",
      "Menyimpan glukosa"
    ],
    "correct_index": 0,
    "explanation": "Klorofil bertugas menyerap energi cahaya matahari yang kemudian digunakan untuk mengubah CO₂ dan air menjadi glukosa. Jawaban lainnya merupakan hasil atau proses yang terjadi setelah penyerapan cahaya."
  }
]
```

#### 4.3.2 Edit & Manajemen Soal

Setelah generate, Owner bisa:
- Edit teks pertanyaan
- Edit teks setiap opsi jawaban
- Ubah jawaban yang benar
- Edit teks explanation (ditampilkan ke peserta saat jawaban salah)
- Tambah soal manual (tanpa AI)
- Hapus soal tertentu
- Reorder soal (drag & drop)
- Publish / Unpublish kuis

#### 4.3.3 Detail Konfigurasi Kuis

Setelah generate, masih bisa diubah:
- Deadline
- Status Aktif/Nonaktif
- Batas Retry
- Minimal Nilai Lulus
- Akses Peserta

#### 4.3.4 Daftar Kuis
- Tabel: Judul, Kategori, Jumlah Soal, Nilai Lulus, Deadline, Status, Jumlah Peserta, Aksi
- Filter: Kategori, Status, Tingkat Kesulitan
- Search

### 4.4 Manajemen Peserta (User Management Tenant)

- **Undang Peserta**: via email (kirim link registrasi ke tenant)
- **Tambah Manual**: nama, email, password sementara
- **Import CSV/Excel**: upload file daftar peserta
- **Edit Data Peserta**
- **Aktif / Nonaktifkan Peserta**
- **Reset Password**
- **Lihat Riwayat Kuis** per peserta: kuis apa saja, kapan, nilai berapa, berapa kali mencoba

### 4.5 Laporan & Analitik

- **Per Kuis**: daftar peserta yang mengerjakan, nilai masing-masing, status lulus/tidak, jumlah percobaan
- **Per Peserta**: semua kuis yang diikuti, nilai, timestamp
- **Export**: ke Excel / PDF
- **Grafik**: distribusi nilai, rata-rata, pass rate

### 4.6 Pengaturan Tenant (Owner Settings)

- Nama Organisasi / Tenant
- Logo Tenant
- Timezone Tenant
- Notifikasi:
  - Email notif ke peserta saat kuis baru tersedia
  - WA notif saat kuis baru tersedia
  - Email/WA notif hasil kuis ke peserta
- LLM Override (opsional): jika SuperAdmin mengizinkan, Owner bisa atur LLM provider sendiri
- WhatsApp Gateway (opsional override)

---

## 5. MODUL USER / PESERTA

### 5.1 Registrasi & Login
- Login via email + password
- Registrasi via link undangan dari Owner
- (Opsional) Login via Google/SSO — roadmap

### 5.2 Dashboard Peserta
- Daftar kuis yang tersedia (publik atau yang di-assign)
- Status per kuis: Belum Dikerjakan / Sedang / Selesai / Tidak Lulus
- Nilai terakhir & status lulus/gagal
- Tombol: Mulai Kuis / Coba Lagi

### 5.3 Pengerjaan Kuis

#### Alur Pengerjaan
1. Peserta klik "Mulai Kuis"
2. Sistem cek: apakah deadline sudah lewat? Apakah masih ada sisa percobaan?
3. Sistem buat record `quiz_attempts` baru: catat `started_at = now()`, hitung `total_duration_seconds`
4. Soal ditampilkan satu per satu (navigasi next/prev) dengan timer berjalan di pojok atas
5. **Percobaan pertama**: soal ditampilkan sesuai urutan asli
6. **Retry**: urutan soal di-acak (randomize), urutan opsi jawaban per soal juga di-acak
7. Setiap jawaban yang dipilih → **auto-save ke server** via Livewire (jawaban tidak hilang jika refresh)
8. Submit manual atau timer habis → sistem hitung nilai → tampilkan hasil langsung

#### Tampilan Hasil
- Nilai (skor 0–100)
- Status: **LULUS** / **TIDAK LULUS**
- Persentase jawaban benar
- Keterangan akhir ujian: `Selesai` / `Waktu Habis` / `Keluar dari Ujian`
- Breakdown per soal:
  - Pertanyaan
  - Jawaban yang dipilih peserta
  - Jawaban yang benar
  - **Explanation** (penjelasan cara menjawab dengan benar) — hanya muncul jika peserta menjawab salah
- Tombol "Coba Lagi" (jika masih ada sisa percobaan dan belum lulus)

#### Aturan Retry
- Jika batas percobaan = N → peserta bisa coba maksimal N kali total
- Jika batas = 0 / unlimited → bebas coba ulang
- Setiap retry: soal & urutan opsi jawaban diacak ulang secara independen
- Jika sudah lulus → tombol "Coba Lagi" tidak muncul (kecuali Owner mengizinkan di setting)

---

### 5.4 Sistem Timer Ujian

Ini adalah salah satu komponen paling kritis dari sistem. Timer bersifat **server-authoritative** — klien hanya menampilkan, server yang menentukan kebenaran waktu.

#### 5.4.1 Kalkulasi Durasi Timer

```
IF quiz.duration_minutes IS NOT NULL:
    total_seconds = quiz.duration_minutes × 60
ELSE:
    total_seconds = quiz.question_count × system_setting.default_seconds_per_question
                  (default: 60 detik per soal)
```

Nilai `total_seconds` disimpan ke kolom `quiz_attempts.total_duration_seconds` saat attempt dibuat.

#### 5.4.2 Timer Server-Side (Authoritative)

- Saat attempt dibuat: `started_at = NOW()` disimpan di database
- Sisa waktu selalu dihitung dari server:
  ```
  remaining_seconds = total_duration_seconds - DATEDIFF(NOW(), started_at, 'SECOND')
  ```
- Klien **tidak bisa memanipulasi timer** — semua keputusan berbasis `started_at` di server
- Jika `remaining_seconds ≤ 0` → attempt otomatis ditutup dan di-submit

#### 5.4.3 Timer di Sisi Klien (Display)

- Saat halaman quiz dimuat: frontend menerima `remaining_seconds` dari server
- **Alpine.js** menjalankan countdown lokal setiap detik (tidak perlu server tiap detik)
- Setiap **30 detik**: Livewire melakukan sync ke server untuk validasi sisa waktu (koreksi drift clock)
- Jika sync menunjukkan perbedaan > 3 detik → klien koreksi ke nilai server
- Tampilan timer: `MM:SS` — berubah warna kuning saat < 2 menit, merah saat < 30 detik

#### 5.4.4 Perilaku Saat Tab Berpindah / Browser Ditutup (Anti-Cheat)

| Skenario | Deteksi | Aksi |
|---|---|---|
| Pindah ke tab lain | `visibilitychange` → `hidden` | Ujian langsung berakhir, submit otomatis dengan jawaban yang sudah ada |
| Minimize browser | `visibilitychange` → `hidden` | Ujian langsung berakhir, submit otomatis |
| Tutup tab / browser | `beforeunload` + `sendBeacon` | Ujian langsung berakhir, submit otomatis |
| Buka DevTools (opsional) | `window resize` heuristic | Bisa dikonfigurasi Owner untuk mencatat sebagai pelanggaran |

**Implementasi teknis tab detection:**
```javascript
// Deteksi tab switch / minimize — paling reliable
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'hidden') {
        navigator.sendBeacon('/api/quiz/attempt/{id}/force-submit', JSON.stringify({
            reason: 'tab_switch'
        }));
    }
});

// Deteksi browser close / tutup tab
window.addEventListener('beforeunload', (e) => {
    navigator.sendBeacon('/api/quiz/attempt/{id}/force-submit', JSON.stringify({
        reason: 'browser_close'
    }));
});
```

- `navigator.sendBeacon()` dipilih karena **dijamin terkirim** meski halaman sedang unload (berbeda dengan `fetch` / `XHR`)
- Server endpoint `/force-submit` bersifat idempotent: jika attempt sudah selesai, request diabaikan
- Hasil ujian tetap dihitung dari jawaban yang sudah ter-save (auto-save per soal)
- Peserta melihat notifikasi pada halaman berikutnya: *"Ujian Anda telah berakhir karena meninggalkan halaman ujian"*

#### 5.4.5 Perilaku Saat Refresh / Koneksi Terputus (Toleran)

Ini dibedakan dari skenario di atas karena dianggap **tidak disengaja**.

**Alur saat halaman di-refresh atau koneksi terputus:**
```
1. User refresh / koneksi putus → halaman reload
2. Halaman quiz dimuat ulang
3. Livewire komponen check: apakah ada active attempt untuk quiz ini?
4. Server query: SELECT * FROM quiz_attempts 
   WHERE user_id = ? AND quiz_id = ? AND status = 'in_progress'
5. Jika ditemukan:
   a. Hitung remaining_seconds = total_duration_seconds - elapsed(started_at, NOW())
   b. IF remaining_seconds > 0:
      → Lanjutkan ujian dengan timer yang sudah berkurang (tepat)
      → Tampilkan kembali soal yang belum dijawab, jawaban yang sudah ada tetap tersimpan
   c. IF remaining_seconds ≤ 0:
      → Submit otomatis attempt dengan jawaban yang ada
      → Redirect ke halaman hasil
6. Jika tidak ditemukan → redirect ke halaman daftar kuis
```

**Yang menjamin ini bekerja:**
- Setiap jawaban di-save ke `quiz_answers` secara real-time (auto-save per klik)
- Timer berjalan di server sejak `started_at`, tidak bergantung pada klien
- Tidak ada `pause` pada timer — refresh = timer tetap jalan

#### 5.4.6 Timer Habis (Auto-Submit)

**Deteksi ganda (double-check):**

1. **Client-side**: Alpine.js countdown mencapai 0 → trigger Livewire action `submitQuiz(reason: 'time_up')` → server submit
2. **Server-side safety net**: Laravel Scheduled Command berjalan setiap **1 menit** (`schedule:run`):
   ```
   Quiz attempts where status = 'in_progress'
   AND (started_at + total_duration_seconds) < NOW()
   → Auto-submit dengan jawaban yang ada, set status = 'timeout'
   ```
   Ini menangani kasus di mana klien tidak pernah terdeteksi (e.g., perangkat mati mendadak)

**Tampilan ke peserta saat waktu habis:**
- Alert modal muncul: *"Waktu ujian telah habis. Jawaban Anda telah dikumpulkan secara otomatis."*
- Redirect otomatis ke halaman hasil dalam 3 detik

#### 5.4.7 Kolom Database untuk Timer

**Tabel `quizzes` (Tenant DB) — tambahan:**
| Kolom | Tipe | Keterangan |
|---|---|---|
| `duration_minutes` | integer nullable | Durasi custom dalam menit. NULL = pakai default per soal |

**Tabel `quiz_attempts` (Tenant DB) — tambahan:**
| Kolom | Tipe | Keterangan |
|---|---|---|
| `started_at` | timestamp | Waktu mulai attempt — dasar kalkulasi timer server |
| `submitted_at` | timestamp nullable | Waktu submit (manual/auto) |
| `total_duration_seconds` | integer | Total waktu yang diizinkan, dihitung saat attempt dibuat |
| `status` | enum | `in_progress` / `submitted` / `timeout` / `force_ended` |
| `end_reason` | enum nullable | `manual` / `time_up` / `tab_switch` / `browser_close` / `admin_force` |
| `is_flagged` | boolean | True jika terdeteksi pelanggaran (tab switch, dll) |

### 5.5 Riwayat Kuis Peserta
- Daftar semua kuis yang pernah dikerjakan
- Nilai per percobaan, tanggal, durasi pengerjaan, status akhir (Selesai / Waktu Habis / Keluar)
- Bisa lihat detail hasil per percobaan
- Jika attempt di-flag (`is_flagged = true`) → tampilkan catatan: *"Ujian ini berakhir karena perpindahan tab"*

---

## 6. SISTEM LLM & PROMPT ENGINEERING

### 6.1 Prompt Template (sistem)
```
Kamu adalah pembuat soal kuis profesional. Buat {jumlah_soal} soal pilihan ganda 
tentang "{topik}" untuk tingkat kesulitan {kesulitan}.

Setiap soal harus memiliki:
- 1 pertanyaan yang jelas
- {jumlah_opsi} pilihan jawaban (A, B, C, D...)
- 1 jawaban yang benar (index dimulai dari 0)
- Penjelasan singkat mengapa jawaban tersebut benar dan mengapa pilihan lain salah

Kembalikan HANYA array JSON dengan format berikut, tanpa teks tambahan apapun:
[
  {
    "question": "...",
    "options": ["...", "...", "...", "..."],
    "correct_index": 0,
    "explanation": "..."
  }
]
```

### 6.2 Provider Strategy
- Coba provider utama → jika gagal/timeout → fallback ke provider berikutnya
- Retry otomatis maksimal 3x jika response bukan JSON valid
- Log semua request & response (anonymized) untuk debugging

### 6.3 Kalkulasi Token Konsumsi
```
Token dikonsumsi = jumlah_soal × token_per_question (setting SuperAdmin)
```
Pengecekan dilakukan sebelum request dikirim ke LLM. Jika saldo tidak cukup → tampilkan error dengan saran beli token.

---

## 7. SISTEM TOKEN

### 7.1 Lifecycle Token

```
Registrasi Owner
       ↓
Dapat free_token_on_register (default: 50)
       ↓
Generate Soal → Kurangi token
       ↓
Saldo habis → Beli paket token
       ↓
Pembayaran sukses → Token bertambah
```

### 7.2 Tabel Token (Central DB)

**owner_token_balances**
| Kolom | Tipe | Keterangan |
|---|---|---|
| owner_id | FK | |
| balance | integer | Saldo saat ini |
| is_unlimited | boolean | Flag unlimited dari SuperAdmin |
| updated_at | timestamp | |

**token_transactions**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | UUID | |
| owner_id | FK | |
| type | enum | `debit` / `credit` |
| amount | integer | Jumlah token |
| source | enum | `register` / `purchase` / `manual_topup` / `quiz_generate` |
| reference_id | string | ID quiz atau ID pembayaran |
| note | text | Keterangan |
| created_at | timestamp | |

### 7.3 Aturan Unlimited Token
- Jika `is_unlimited = true` → skip semua pengecekan & deduction token
- Kolom `balance` tetap ada tapi tidak berubah
- Di dashboard Owner unlimited: tampilkan badge "Unlimited" daripada saldo angka

---

## 8. PAYMENT GATEWAY

### 8.1 Flow Pembelian Token

```
Owner pilih paket
       ↓
Pilih payment gateway
       ↓
Sistem buat order (status: pending)
       ↓
Redirect ke halaman pembayaran gateway
       ↓
Pembayaran selesai
       ↓
Gateway kirim webhook ke sistem
       ↓
Sistem verifikasi webhook
       ↓
Update order (status: success)
       ↓
Tambah token ke saldo Owner
       ↓
Kirim notifikasi email + WA ke Owner
```

### 8.2 Tabel Pembayaran (Central DB)

**token_orders**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | UUID | |
| owner_id | FK | |
| package_id | FK | Paket token yang dibeli |
| token_amount | integer | Jumlah token dalam paket |
| amount_idr | integer | Harga dalam rupiah |
| gateway | enum | midtrans/xendit/ipaymu/doku/duitku |
| gateway_order_id | string | ID order dari gateway |
| status | enum | pending/success/failed/expired |
| paid_at | timestamp | |
| expired_at | timestamp | |
| created_at | timestamp | |

### 8.3 Driver Pattern untuk Gateway
Setiap gateway diimplementasikan sebagai class driver yang implement interface:
```php
interface PaymentGatewayContract {
    public function createOrder(TokenOrder $order): array;
    public function verifyWebhook(Request $request): bool;
    public function getPaymentUrl(array $data): string;
}
```
Mudah menambah gateway baru cukup buat class driver baru.

---

## 9. NOTIFIKASI

### 9.1 Event Notifikasi

| Event | Email | WhatsApp |
|---|---|---|
| Owner registrasi berhasil | ✅ | ✅ |
| Pembelian token berhasil | ✅ | ✅ |
| Token saldo menipis (< threshold) | ✅ | ✅ |
| Kuis baru tersedia (ke peserta) | ✅ | ✅ |
| Peserta selesai kuis (ke owner, opsional) | ✅ | - |
| Peserta diundang ke tenant | ✅ | ✅ |
| Deadline kuis akan tiba (H-1) | ✅ | ✅ |

### 9.2 Template Notifikasi
- SuperAdmin bisa edit template email (HTML editor)
- Template WA: teks biasa dengan variabel `{nama}`, `{kuis}`, `{nilai}`, dll.
- Setiap Owner bisa on/off notifikasi tertentu di setting tenant

### 9.3 Queue
Semua notifikasi dikirim via Laravel Queue (async) agar tidak memblokir request user.

---

## 10. DATABASE SCHEMA (RINGKASAN)

### Central Database
- `users` (SuperAdmin)
- `owners` (akun owner + tenant info)
- `tenants` (stancl/tenancy)
- `domains` (stancl/tenancy)
- `owner_token_balances`
- `token_transactions`
- `token_packages`
- `token_orders`
- `llm_providers`
- `payment_gateway_configs`
- `whatsapp_gateway_configs`
- `email_gateway_configs`
- `system_settings`
- `activity_logs`

### Tenant Database (per Owner)
- `users` (peserta/participant)
- `quizzes` — termasuk: `duration_minutes` (nullable), `passing_score`, `retry_limit`, `is_public`, `deadline_at`
- `questions` — termasuk: `order`, `difficulty`
- `question_options` — termasuk: `is_correct`, `explanation`
- `quiz_participants` (relasi kuis ↔ peserta, jika tidak publik)
- `quiz_attempts` — termasuk: `started_at`, `submitted_at`, `total_duration_seconds`, `status`, `end_reason`, `is_flagged`, `score`
- `quiz_answers` — termasuk: `question_id`, `selected_option_id`, `is_correct`, `answered_at`
- `notifications_log`

---

## 11. KEAMANAN & INTEGRITAS UJIAN

### 11.1 Keamanan Umum
- **Multi-tenancy isolation**: query otomatis di-scope per tenant (stancl/tenancy)
- **CSRF Protection**: aktif di semua form dan endpoint Livewire
- **Rate Limiting**: endpoint generate soal dibatasi (e.g., 10 req/menit per Owner)
- **Webhook Verification**: setiap webhook payment gateway diverifikasi signature/HMAC
- **API Key Encryption**: semua API key tersimpan terenkripsi (`encrypted` cast) di database
- **Input Sanitization**: semua input user di-sanitize sebelum proses
- **Role & Permission**: Spatie/laravel-permission dengan policy per resource
- **SQL Injection**: Eloquent ORM + parameterized queries only
- **XSS Protection**: Blade auto-escaping + CSP header

### 11.2 Integritas Timer & Anti-Cheat Ujian
- **Server-authoritative timer**: semua keputusan waktu berdasarkan `started_at` server, tidak bisa dimanipulasi client
- **Auto-submit on tab switch**: `navigator.sendBeacon()` digunakan untuk memastikan request terkirim meski halaman sedang ditutup
- **Idempotent force-submit endpoint**: request submit ganda diabaikan (mencegah race condition)
- **Scheduled safety net**: Laravel Scheduler cek setiap menit untuk attempt yang expired tapi belum di-submit
- **Flag pelanggaran**: setiap attempt yang berakhir karena tab switch/browser close di-flag dan tercatat di laporan Owner
- **Auto-save jawaban**: jawaban tersimpan per klik, mencegah kehilangan data saat koneksi putus
- **Attempt lock**: satu peserta hanya boleh punya satu `in_progress` attempt per kuis pada satu waktu
- **Replay protection**: setelah attempt berakhir, endpoint submit tidak bisa dipanggil lagi untuk attempt yang sama

---

## 12. ALUR REGISTRASI OWNER

```
1. Owner akses halaman registrasi publik
2. Isi form: nama, email, password, nama organisasi
3. Sistem:
   a. Buat akun owner di central DB
   b. Buat tenant baru (stancl/tenancy)
   c. Buat domain/subdomain tenant
   d. Jalankan migrasi tenant database
   e. Tambah free token (free_token_on_register)
   f. Kirim email verifikasi
4. Owner verifikasi email
5. Owner login ke dashboard tenant mereka
```

---

## 13. API ENDPOINTS (Internal)

Selain web routes, sistem menyediakan API untuk keperluan integrasi:

### Central (SuperAdmin)
- `POST /api/admin/owners` — buat owner
- `PATCH /api/admin/owners/{id}/toggle-unlimited` — toggle unlimited token
- `POST /api/admin/owners/{id}/topup` — top-up token manual

### Tenant (Owner)
- `POST /api/tenant/quizzes/generate` — trigger generate soal AI
- `GET /api/tenant/quizzes` — daftar kuis
- `GET /api/tenant/quizzes/{id}/results` — hasil kuis

### Quiz Attempt (Peserta — Timer Endpoints)
- `POST /{slug}/quiz/{id}/attempt/start` — mulai attempt baru, returns `attempt_id` + `remaining_seconds`
- `GET /{slug}/quiz/attempt/{id}/remaining` — sync sisa waktu dari server (dipanggil setiap 30 detik)
- `POST /{slug}/quiz/attempt/{id}/answer` — auto-save jawaban satu soal
- `POST /{slug}/quiz/attempt/{id}/submit` — submit manual
- `POST /{slug}/quiz/attempt/{id}/force-submit` — submit paksa (tab switch / browser close, via sendBeacon, no CSRF needed, signed token)

### Webhook (Payment)
- `POST /webhook/midtrans`
- `POST /webhook/xendit`
- `POST /webhook/ipaymu`
- `POST /webhook/doku`
- `POST /webhook/duitku`

---

## 14. ROADMAP & FASE PENGEMBANGAN

### Fase 1 — MVP (Core)
- [x] Multi-tenant setup (stancl/tenancy, path-based)
- [x] Auth: SuperAdmin, Owner, Peserta
- [x] LLM Integration (OpenRouter sebagai default, + DeepSeek, Custom)
- [x] Sistem Token (free + deduction + unlimited flag)
- [x] Generate Soal AI (pilihan ganda + explanation)
- [x] Pengerjaan Kuis + Retry + Randomize soal & opsi
- [x] **Timer ujian server-side** (auto-submit, tab detection, refresh-tolerant)
- [x] Auto-save jawaban per klik
- [x] Tampil Nilai & Explanation langsung
- [x] 1 Payment Gateway (Midtrans)
- [x] Email Notifikasi (SMTP)

### Fase 2 — Lengkap
- [ ] Semua Payment Gateway (Xendit, iPaymu, DOKU, Duitku)
- [ ] WhatsApp Notifikasi (Fonnte & Wablast)
- [ ] Import peserta CSV
- [ ] Laporan & Export Excel/PDF (termasuk laporan flag pelanggaran)
- [ ] Override LLM per Owner
- [ ] Laporan pelanggaran timer per attempt di dashboard Owner

### Fase 3 — Advanced
- [ ] Soal tipe Essay (AI scoring)
- [ ] Sertifikat otomatis (PDF) saat lulus
- [ ] Login SSO (Google)
- [ ] Mobile-first responsive redesign
- [ ] Proctoring sederhana (tab-switch detection)
- [ ] Leaderboard per kuis
- [ ] API publik untuk integrasi eksternal

---

## 15. KRITERIA PENERIMAAN (ACCEPTANCE CRITERIA)

### SuperAdmin
- ✅ Bisa mengaktifkan/menonaktifkan Owner
- ✅ Bisa set token gratis awal (berlaku untuk registrasi baru)
- ✅ Bisa toggle `unlimited_token` per Owner
- ✅ Bisa top-up token manual ke Owner manapun
- ✅ Semua payment gateway bisa dikonfigurasi via panel
- ✅ LLM provider bisa dikonfigurasi dengan provider custom
- ✅ Log transaksi token dan log generate soal tersimpan lengkap

### Owner
- ✅ Token berkurang setiap kali soal berhasil di-generate
- ✅ Jika saldo tidak cukup, generate diblokir dengan pesan jelas
- ✅ Owner unlimited tidak terkena deduction dan tidak melihat saldo angka
- ✅ Soal yang di-generate bisa diedit sebelum dipublish
- ✅ Kuis bisa diatur akses: publik atau peserta tertentu
- ✅ Bisa membeli token via payment gateway yang aktif

### Peserta
- ✅ Soal diacak setiap retry (urutan soal & opsi jawaban)
- ✅ Nilai dan explanation muncul langsung setelah submit
- ✅ Tidak bisa retry jika sudah melebihi batas percobaan
- ✅ Tidak bisa mengakses kuis yang sudah melewati deadline

### Timer
- ✅ Timer berjalan berdasarkan `started_at` di server — tidak bisa dimanipulasi klien
- ✅ Jika kuis tidak punya durasi custom, timer = jumlah soal × `default_seconds_per_question`
- ✅ Jika kuis punya durasi custom (N menit), timer = N × 60 detik
- ✅ Pindah tab → ujian langsung berakhir + submit otomatis (via `visibilitychange` + `sendBeacon`)
- ✅ Tutup browser → ujian langsung berakhir + submit otomatis (via `beforeunload` + `sendBeacon`)
- ✅ Refresh halaman / koneksi putus → ujian bisa dilanjutkan, timer sisa dihitung ulang dari server
- ✅ Jawaban yang sudah dipilih sebelum refresh tetap tersimpan (auto-save per klik)
- ✅ Timer klien (Alpine.js) tersinkronisasi dengan server setiap 30 detik
- ✅ Saat countdown mencapai 0 → submit otomatis dari klien
- ✅ Laravel Scheduler sebagai safety net: cek expired attempt setiap menit, auto-submit jika belum ter-submit
- ✅ Attempt yang berakhir karena tab switch / browser close di-flag di database dan laporan Owner

---

## 16. PERTIMBANGAN TEKNIS TAMBAHAN

### Performance
- Generate soal via **Laravel Queue + Redis** (async) — response ke user cepat, proses LLM di background
- Livewire polling hanya untuk sync timer (setiap 30 detik), bukan tiap detik — mengurangi server load
- Alpine.js countdown murni client-side untuk tampilan per detik (zero server hit)
- Index database pada: `quiz_attempts.status`, `quiz_attempts.started_at`, `quiz_answers.attempt_id`

### Timer Architecture Summary
```
Client (Alpine.js)           Server (Laravel)
─────────────────           ────────────────
remaining = X detik    ←    started_at disimpan di DB
countdown per detik          
                             
setiap 30 detik  ──────→    GET /attempt/{id}/remaining
                 ←──────    { remaining_seconds: Y }
client koreksi ke Y          

countdown = 0  ─────────→   POST /attempt/{id}/submit
                   ATAU
                             Scheduler (tiap menit)
                             → auto-submit expired attempts

visibilitychange:hidden ──→  POST /attempt/{id}/force-submit (sendBeacon)
beforeunload            ──→  POST /attempt/{id}/force-submit (sendBeacon)
```

### Skalabilitas
- Driver-based pattern untuk semua gateway (payment, WhatsApp, LLM) → mudah tambah provider baru
- Tenant database bisa dipisah ke server berbeda (stancl/tenancy mendukung ini)
- Cache konfigurasi sistem (Redis) — setting tidak di-query DB setiap request
- Queue worker terpisah untuk: generate soal, notifikasi, auto-submit timer

### Monitoring
- Error logging: **Sentry** (production) + **Laravel Telescope** (development)
- Queue monitoring: **Laravel Horizon**
- Scheduled jobs: log hasil scheduler ke tabel `scheduler_logs`
- Uptime monitoring: health check endpoint `GET /health`
- Timer audit trail: semua force-submit tercatat di `quiz_attempts.end_reason`

---

*Dokumen ini dibuat sebagai PRD lengkap untuk pengembangan LMS AI-Powered berbasis Laravel 12 dengan arsitektur multi-tenant. Dapat diserahkan ke AI coding agent atau tim developer sebagai panduan implementasi.*

**Versi**: 1.1  
**Dibuat**: Juli 2026  
**Stack**: Laravel 12 · stancl/tenancy (path-based) · MySQL · Redis · Blade + Livewire 3 · Alpine.js  
**LLM Default**: OpenRouter · **Frontend**: Blade + Livewire · **Tenant Mode**: Path-based (`domain.com/{slug}/`)
