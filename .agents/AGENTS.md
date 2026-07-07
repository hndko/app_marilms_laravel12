# Project Rules — MariLMS AI

Dokumen ini berisi aturan kerja wajib (*Rules & Conventions*) bagi AI Agent maupun developer dalam mengembangkan aplikasi **MariLMS AI**. Dokumen ini bertindak sebagai pedoman utama (*single source of truth*) untuk seluruh keputusan penulisan kode, standar desain UI/UX, dan arsitektur sistem.

---

## BAGIAN I: INFORMASI PROJECT

### 1. Deskripsi & Latar Belakang
- **Nama Project:** MariLMS AI (*AI-Powered Multi-Tenant Learning Management System & Exam Platform*)
- **Objektif:** Platform SaaS evaluasi dan ujian digital berbasis kecerdasan buatan yang membantu institusi pendidikan (sekolah, perguruan tinggi, bimbel) membuat soal evaluasi otomatis, menyelenggarakan ujian anti-cheat, dan menganalisis kelulusan siswa secara efisien.
- **Product Positioning:** MariLMS AI adalah platform ujian dan evaluasi akademik enterprise. Setiap lembaga mendapat workspace mandiri (multi-tenant terisolasi) dengan portal ujian, manajemen siswa, dan analitik tersendiri.
- **Alur Utama:** `Landing Page` → `Owner Register/Login` → `Setup Tenant` → `Create Quiz via AI` → `Invite Participants` → `Exam Session (Anti-Cheat)` → `Results & Analytics`.

### 2. Tech Stack & Arsitektur
- **Backend:** PHP 8.2+, Laravel 12.
- **Frontend:** Laravel Blade, Tailwind CSS v4.0, Alpine.js (Arsitektur ringan, cepat, tanpa framework JS berat berlebihan).
- **Database:** MySQL 8.0+ / MariaDB 10.5+.
- **Cache & Queue:** Redis (untuk Queue Workers & Session Caching).
- **Multi-Tenancy:** `stancl/tenancy` (path-based tenancy `{tenant}/...`).
- **AI Integration:** OpenRouter / DeepSeek / Custom Base URL + API Key (Mendukung fleksibilitas pergantian provider LLM dengan fallback chain).
- **Payment:** Midtrans (Snap.js) untuk pembelian token AI.
- **WhatsApp Notification:** Fonnte & Wablast Gateway (7 event notifikasi otomatis).

### 3. Struktur Roles (3-Tier Auth Guards)
1. **SuperAdmin (Guard: `web`):** Mengelola seluruh platform, tenant, LLM providers, payment gateways, token packages, dan system settings.
2. **Owner / Pengajar (Guard: `owner`):** Membuat kuis AI, mengelola peserta, top-up token, konfigurasi WhatsApp, dan analitik tenant.
3. **Participant / Siswa (Guard: Tenant `web`):** Mengerjakan ujian dengan proteksi anti-cheat (server-authoritative timer, tab switch detection, force-submit).

---

## BAGIAN II: ATURAN KERJA AGENT (RULES & CONVENTIONS)

Jika terdapat konflik antara kebiasaan umum penulisan kode dengan aturan di bawah ini, maka aturan pada dokumen `.agents/AGENTS.md` ini yang **wajib diprioritaskan**.

### 1. Pedoman Utama & Bahasa Komunikasi
- **Single Source of Truth:** Seluruh pengembangan kode dan UI wajib mengacu pada `.agents/AGENTS.md`, `PRD_LMS_AI_Laravel12.md`, dan dokumentasi terkait.
- **Aturan Bahasa (Wajib):**
  - Seluruh elemen antarmuka yang dibaca oleh pengguna (*user-facing text*), seperti teks halaman web, **alert**, notifikasi *toast*, pesan error, *empty state*, label form, *placeholder*, dan teks disclaimer **WAJIB menggunakan Bahasa Indonesia** yang formal, profesional, jelas, dan baku.
  - Untuk struktur file, penamaan variabel, *function/method*, *controller*, *model*, migrasi database, dan komentar kode teknis diperbolehkan dan disarankan menggunakan **Bahasa Inggris** sesuai standar ekosistem Laravel.
- **Integritas Kode:** Lakukan perubahan kode secara terisolasi dan spesifik. Dilarang menghapus fitur, komentar, atau file lain yang tidak terkait langsung dengan instruksi kerja.

### 2. Aturan UI/UX & Design Philosophy
- **Konsep Visual:** *Modern Enterprise, Flat Design, Professional, Trust, Fast, Clean, Minimal*. Tampilan harus terasa seperti aplikasi bisnis tingkat enterprise (sekelas Stripe, GitHub, Notion, Linear, atau Cloudflare).
- **Pantangan Keras Desain (*Strictly Forbidden*):**
  - ❌ **Dilarang** menggunakan *Glassmorphism, Neon UI, Heavy Gradients, Blur Background*, atau efek *Morphism/Skeuomorphism*.
  - ❌ **Dilarang** membuat UI yang terlihat seperti *AI Chatbot standar, Crypto Dashboard, Gaming UI*, atau *Landing Page* startup yang berlebihan.
  - ❌ **Dilarang** menggunakan animasi berlebihan (*No bounce, zoom, rotate, pulse, floating*). Gunakan transisi halus maksimal 150ms *ease* untuk efek *hover*.
  - ❌ **Dilarang** menggunakan ilustrasi AI stereotipikal (seperti gambar robot, otak bercahaya, sirkuit komputer, atau *neural network*).
  - ❌ **Dilarang** menggunakan *Speedometer, Gauge*, atau *Pie Chart* untuk indikator skor/reputasi. Gunakan angka besar bergaya tipografi bersih dan *Progress Bar* vertikal/horizontal.
- **Palet Warna & Elemen UI (Tailwind CSS v4):**
  - *Primary:* Blue 600 (`#2563EB`), Hover: `#1D4ED8`.
  - *Background:* Neutral Light (`#F8FAFC`), Surface/Card: White (`#FFFFFF`).
  - *Text:* Primary (`#0F172A`), Secondary (`#475569`), Muted (`#64748B`).
  - *Status Alert/Badge:* Success Green (`#16A34A`), Warning Orange (`#D97706`), Danger Red (`#DC2626`), Info Blue (`#0284C7`).
  - *Border & Radius:* Card dibatasi dengan *border* tipis (`1px solid #E5E7EB`) dan *shadow* sangat halus (`shadow-sm` atau `shadow-md`). Border radius maksimal `rounded-xl`.
- **Frontend Stack (Wajib Konsisten):**
  - **Tailwind CSS v4.0**: Styling utama. Jangan pakai CDN; install via `npm` dan bundle lewat Vite.
  - **Alpine.js**: Interaktivitas klien ringan. Jangan pakai CDN; install via `npm` dan import di `app.js`.
  - **Laravel Blade**: Templating engine utama. Gunakan component-based architecture.

### 3. Aturan Arsitektur & Kode
- **Multi-Tenancy:** Gunakan `stancl/tenancy` dengan path-based routing. Setiap tenant memiliki database mandiri. Central database menyimpan data superadmin, owners, token transactions, dan system settings.
- **Token Service:** Gunakan `lockForUpdate()` pada transaksi token untuk mencegah *race condition*. Semua operasi debit/kredit token harus melalui `TokenService`.
- **LLM Service:** Gunakan *fallback chain* pattern. Jika provider utama gagal, otomatis beralih ke provider berikutnya. Konfigurasi disimpan di database (`llm_providers`), bukan hardcoded di `.env`.
- **Quiz Timer:** Menggunakan *server-authoritative timer* murni. Waktu berjalan dan sisa waktu dihitung dari server, bukan dari clock client. Anti-cheat tab switch detection wajib aktif saat ujian berlangsung.
- **Payment Webhook:** Verifikasi signature wajib dilakukan pada setiap webhook callback dari payment gateway.

### 4. Automatic Git Commit & Push
- Setiap kali sebuah tugas (task/phase) selesai dikerjakan atau diimplementasikan, wajib secara otomatis melakukan proses git staging, commit, dan push (`git add .`, `git commit -m "..."`, dan `git push`) ke remote repository tanpa perlu diminta kembali oleh user.

### 5. Automatic Rule Recording & README Synchronization
- Setiap kali ada perubahan aturan atau kebijakan baru proyek yang ditentukan oleh user, wajib langsung mencatatnya ke dalam berkas `.agents/AGENTS.md` ini.
- Setelah mencatat aturan baru di `.agents/AGENTS.md`, wajib secara otomatis memperbarui dokumen `README.md` (misalnya pada bagian Kontribusi atau Aturan Pengembangan) agar dokumentasi selalu sinkron dengan aturan terbaru.
