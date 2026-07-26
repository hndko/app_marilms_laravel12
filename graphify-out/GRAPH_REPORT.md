# Graph Report - app_marilms_laravel12  (2026-07-26)

## Corpus Check
- 328 files · ~494,941 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1394 nodes · 2069 edges · 269 communities (219 shown, 50 thin omitted)
- Extraction: 95% EXTRACTED · 5% INFERRED · 0% AMBIGUOUS · INFERRED: 109 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f2484b15`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Owner
- LlmProvider
- dependencies
- composer.json
- Illuminate\Http\Request
- WaGatewayContract.php
- User
- package.json
- TokenPackage
- scripts
- TailAdmin Laravel - Tailwind CSS Free Laravel Dashboard
- QuizAttempt
- Illuminate\Database\Eloquent\Model
- GenerateQuizJob.php
- Closure
- Illuminate\View\Component
- Illuminate\Contracts\View\View
- Quiz
- Controller
- 📘 Panduan Deployment & Worker Queue MariLMS (v1.4.0 - Single-Database Tenancy)
- 🤖 GEMINI.md - Panduan & Aturan Proyek untuk AI Assistant
- scripts
- TokenOrder
- Illuminate\Database\Eloquent\Relations\HasMany
- ParticipantController
- GatewayController
- Question
- SetTenantUrlDefaults.php
- UserFactory
- tailadmin-laravel-main/composer.json
- PaymentWebhookController
- require-dev
- QuizController
- QuizAttempt.php
- MenuHelper
- config
- 3. MODUL SUPERADMIN
- .index
- 🎓 MariLMS AI — Platform LMS & Portal Ujian Multi-Tenant Berbasis AI (v1.5.1)
- Illuminate\Database\Migrations\Migration
- psr-4
- Domain.php
- CalenderArea.php
- DropdownMenu.php
- PageBreadcrumb.php
- Preloader.php
- TableDropdown.php
- EcommerceMetrics.php
- MonthlySale.php
- MonthlyTarget.php
- 5.4 Sistem Timer Ujian
- StatisticsChart.php
- DatePicker.php
- DefaultInputs.php
- Dropzone.php
- InputGroup.php
- SelectInputs.php
- TextAreaInputs.php
- ToggleSwitch.php
- Radio.php
- MultipleSelect.php
- NotificationDropdown.php
- AddressCard.php
- PersonalInfoCard.php
- ProfileCard.php
- BasicTablesFour.php
- BasicTablesOne.php
- BasicTablesThree.php
- BasicTablesTwo.php
- Avatar.php
- Badge.php
- Modal.php
- YoutubeEmbed.php
- post-create-project-cmd
- require
- app.blade.php
- ExampleTest
- extra
- keywords
- tailadmin-laravel-main/app/Http/Controllers/Controller.php
- sidebar.blade.php
- llm/index.blade.php
- Product Requirements Document (PRD)
- 4. MODUL OWNER (TENANT)
- TokenTransaction
- 13. API ENDPOINTS (Internal)
- 15. KRITERIA PENERIMAAN (ACCEPTANCE CRITERIA)
- 16. PERTIMBANGAN TEKNIS TAMBAHAN
- 4.3 Manajemen Kuis (Quiz Management)
- ProfileController
- 14. ROADMAP & FASE PENGEMBANGAN
- 1. RINGKASAN PRODUK
- 2. PERAN & HIERARKI PENGGUNA
- 6. SISTEM LLM & PROMPT ENGINEERING
- 7. SISTEM TOKEN
- 8. PAYMENT GATEWAY
- 9. NOTIFIKASI
- CheckboxComponent.php
- DashboardController
- rules/graphify.md
- workflows/graphify.md

## God Nodes (most connected - your core abstractions)
1. `Controller` - 41 edges
2. `Quiz` - 36 edges
3. `Owner` - 35 edges
4. `QuizAttempt` - 30 edges
5. `LlmProvider` - 28 edges
6. `ActivityLog` - 27 edges
7. `TokenOrder` - 26 edges
8. `PaymentGatewayConfig` - 24 edges
9. `TokenService` - 23 edges
10. `Product Requirements Document (PRD)` - 18 edges

## Surprising Connections (you probably didn't know these)
- `DashboardController` --inherits--> `Controller`  [EXTRACTED]
  docs/tailadmin-laravel-main/app/Http/Controllers/DashboardController.php → app/Http/Controllers/Controller.php
- `SidebarController` --inherits--> `Controller`  [EXTRACTED]
  docs/tailadmin-laravel-main/app/Http/Controllers/SidebarController.php → app/Http/Controllers/Controller.php
- `ExampleTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/ExampleTest.php → docs/tailadmin-laravel-main/tests/TestCase.php
- `QuizAttemptTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Feature/QuizAttemptTest.php → docs/tailadmin-laravel-main/tests/TestCase.php
- `LlmServiceTest` --inherits--> `TestCase`  [EXTRACTED]
  tests/Unit/LlmServiceTest.php → docs/tailadmin-laravel-main/tests/TestCase.php

## Import Cycles
- None detected.

## Communities (269 total, 50 thin omitted)

### Community 0 - "Owner"
Cohesion: 0.06
Nodes (14): AuthController, SettingsController, DashboardController, OwnerController, SettingsController, ActivityLog, Owner, OwnerTokenBalance (+6 more)

### Community 1 - "LlmProvider"
Cohesion: 0.06
Nodes (11): AutoSubmitExpiredAttempts, LlmProviderController, static, LlmProvider, CustomProvider, DeepSeekProvider, OpenRouterProvider, LlmService (+3 more)

### Community 2 - "dependencies"
Cohesion: 0.04
Nodes (46): dependencies, alpinejs, apexcharts, flatpickr, @floating-ui/dom, @fullcalendar/core, @fullcalendar/daygrid, @fullcalendar/interaction (+38 more)

### Community 3 - "composer.json"
Cohesion: 0.04
Nodes (45): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+37 more)

### Community 4 - "Illuminate\Http\Request"
Cohesion: 0.11
Nodes (9): parseWebhook(), verifyWebhook(), PaymentGatewayConfig, DokuGateway, DuitkuGateway, IpaymuGateway, MidtransGateway, XenditGateway (+1 more)

### Community 5 - "WaGatewayContract.php"
Cohesion: 0.12
Nodes (4): NotificationService, FonnteDriver, LogDriver, WablastDriver

### Community 6 - "User"
Cohesion: 0.06
Nodes (20): CreateNewUser, User, ResetUserPassword, UpdateUserPassword, UpdateUserProfileInformation, User, AppServiceProvider, FortifyServiceProvider (+12 more)

### Community 7 - "package.json"
Cohesion: 0.06
Nodes (33): @fortawesome/fontawesome-free, dependencies, alpinejs, apexcharts, flatpickr, @floating-ui/dom, @fortawesome/fontawesome-free, prismjs (+25 more)

### Community 8 - "TokenPackage"
Cohesion: 0.10
Nodes (10): TokenPackageController, TokenPackage, DatabaseSeeder, LlmProviderSeeder, RoleSeeder, SystemSettingSeeder, TokenPackageSeeder, UserSeeder (+2 more)

### Community 9 - "scripts"
Cohesion: 0.08
Nodes (26): Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, @php artisan config:clear --ansi, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php artisan package:discover --ansi, @php artisan test, @php artisan vendor:publish --tag=laravel-assets --ansi --force (+18 more)

### Community 10 - "TailAdmin Laravel - Tailwind CSS Free Laravel Dashboard"
Cohesion: 0.04
Nodes (44): [2025-12-29], [2026-03-15], [2026-05-23], [April 28, 2026], Artisan Commands, 📜 Available Commands, Build Frontend Assets, Building for Production (+36 more)

### Community 12 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.12
Nodes (8): TenantScope, NotificationLog, QuizAnswer, QuizParticipant, Illuminate\Database\Eloquent\Builder, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Scope

### Community 13 - "GenerateQuizJob.php"
Cohesion: 0.09
Nodes (14): GenerateQuizJob, PromptBuilder, TestCase, Illuminate\Bus\Queueable, Illuminate\Contracts\Queue\ShouldQueue, Illuminate\Foundation\Bus\Dispatchable, Illuminate\Foundation\Testing\TestCase, Illuminate\Queue\InteractsWithQueue (+6 more)

### Community 14 - "Closure"
Cohesion: 0.15
Nodes (5): Closure, RecentOrders, RadioButtons, Alert, Button

### Community 15 - "Illuminate\View\Component"
Cohesion: 0.15
Nodes (5): CommonGridShape, ComponentCard, FileInputExample, BasicTablesFive, Illuminate\View\Component

### Community 16 - "Illuminate\Contracts\View\View"
Cohesion: 0.15
Nodes (5): ThemeToggle, CustomerDemographic, InputStates, UserDropdown, Illuminate\Contracts\View\View

### Community 17 - "Quiz"
Cohesion: 0.15
Nodes (4): ReportController, User, Quiz, Illuminate\Database\Eloquent\Relations\BelongsToMany

### Community 18 - "Controller"
Cohesion: 0.12
Nodes (5): Controller, QuizForceSubmitController, LogController, SidebarController, Illuminate\Database\Eloquent\Concerns\HasUuids

### Community 19 - "📘 Panduan Deployment & Worker Queue MariLMS (v1.4.0 - Single-Database Tenancy)"
Cohesion: 0.08
Nodes (23): 1. Install Packages, ⚙️ 1. Manajemen Background Queue Worker (`php artisan queue:work`), 2. Buat Database MySQL, 💻 2. Local Development (Laragon / XAMPP), 3. Clone & Setup Application, ⚡ 3. VPS dengan aaPanel, 4. Konfigurasi Nginx Server Block (`/etc/nginx/sites-available/marilms`), 🖥️ 4. VPS Linux Murni (Ubuntu / Debian + Nginx) (+15 more)

### Community 20 - "🤖 GEMINI.md - Panduan & Aturan Proyek untuk AI Assistant"
Cohesion: 0.10
Nodes (20): 🔄 10. Kewajiban Pengkinian Dokumen (Mandatory Sync), 🏢 1. Ringkasan Proyek, 🛠️ 2. Stack Teknologi & Prasyarat, 🗄️ 3. Arsitektur Database & Tabel Utama, 👥 4. Daftar Peran (Roles) & Guard, 🚨 5. Aturan Wajib untuk AI Assistant (Gemini Guidelines), 💻 6. Perintah Verifikasi Standar, 📐 7. Format Respon API Standardized (+12 more)

### Community 21 - "scripts"
Cohesion: 0.14
Nodes (14): Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, @php artisan config:clear --ansi, @php artisan package:discover --ansi, @php artisan test, @php artisan vendor:publish --tag=laravel-assets --ansi --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\, scripts (+6 more)

### Community 22 - "TokenOrder"
Cohesion: 0.17
Nodes (3): createOrder(), TokenController, TokenOrder

### Community 23 - "Illuminate\Database\Eloquent\Relations\HasMany"
Cohesion: 0.22
Nodes (3): User, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User

### Community 25 - "GatewayController"
Cohesion: 0.13
Nodes (3): GatewayController, EmailGatewayConfig, WhatsappGatewayConfig

### Community 28 - "UserFactory"
Cohesion: 0.25
Nodes (5): static, UserFactory, static, UserFactory, Illuminate\Database\Eloquent\Factories\Factory

### Community 29 - "tailadmin-laravel-main/composer.json"
Cohesion: 0.18
Nodes (10): autoload-dev, psr-4, description, license, minimum-stability, name, prefer-stable, Tests\\ (+2 more)

### Community 30 - "PaymentWebhookController"
Cohesion: 0.29
Nodes (3): PaymentWebhookController, PaymentGatewayManager, Illuminate\Http\JsonResponse

### Community 31 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 35 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 36 - "3. MODUL SUPERADMIN"
Cohesion: 0.10
Nodes (20): 3.10 Log & Audit, 3.1 Dashboard SuperAdmin, 3.2 Manajemen Owner (User Management), 3.3 Konfigurasi Token & Timer Global, 3.4 Paket Token (Token Package), 3.5 Konfigurasi LLM Provider (Global Default), 3.6 Email Gateway, 3.7 WhatsApp Gateway (+12 more)

### Community 38 - "🎓 MariLMS AI — Platform LMS & Portal Ujian Multi-Tenant Berbasis AI (v1.5.1)"
Cohesion: 0.11
Nodes (17): 1. Menjalankan Aplikasi di Lingkungan Lokal, 2. Kredensial Pengujian Awal (Default Credentials), A. Tabel Central (Central Manager), B. Tabel Tenant (Berisi `tenant_id` + `TenantScope`), 📑 Daftar Isi, 💡 Deskripsi Proyek, 🚀 Instalasi, 🤝 Kontribusi (+9 more)

### Community 40 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 50 - "5.4 Sistem Timer Ujian"
Cohesion: 0.12
Nodes (16): 5.1 Registrasi & Login, 5.2 Dashboard Peserta, 5.3 Pengerjaan Kuis, 5.4.1 Kalkulasi Durasi Timer, 5.4.2 Timer Server-Side (Authoritative), 5.4.3 Timer di Sisi Klien (Display), 5.4.4 Perilaku Saat Tab Berpindah / Browser Ditutup (Anti-Cheat), 5.4.5 Perilaku Saat Refresh / Koneksi Terputus (Toleran) (+8 more)

### Community 73 - "post-create-project-cmd"
Cohesion: 0.50
Nodes (4): @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\, post-create-project-cmd

### Community 74 - "require"
Cohesion: 0.50
Nodes (4): require, laravel/framework, laravel/tinker, php

### Community 75 - "app.blade.php"
Cohesion: 0.50
Nodes (3): layouts.app-header, layouts.backdrop, layouts.sidebar

### Community 100 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 101 - "keywords"
Cohesion: 0.67
Nodes (3): framework, laravel, keywords

### Community 249 - "Product Requirements Document (PRD)"
Cohesion: 0.18
Nodes (10): 10. DATABASE SCHEMA (RINGKASAN), 11.1 Keamanan Umum, 11.2 Integritas Timer & Anti-Cheat Ujian, 11. KEAMANAN & INTEGRITAS UJIAN, 12. ALUR REGISTRASI OWNER, AI-Powered LMS (Learning Management System), Central Database, Product Requirements Document (PRD) (+2 more)

### Community 250 - "4. MODUL OWNER (TENANT)"
Cohesion: 0.25
Nodes (8): 4.1 Dashboard Owner, 4.2 Token & Pembelian Token, 4.4 Manajemen Peserta (User Management Tenant), 4.5 Laporan & Analitik, 4.6 Pengaturan Tenant (Owner Settings), 4. MODUL OWNER (TENANT), Beli Token, Tampilan Saldo Token

### Community 252 - "13. API ENDPOINTS (Internal)"
Cohesion: 0.40
Nodes (5): 13. API ENDPOINTS (Internal), Central (SuperAdmin), Quiz Attempt (Peserta — Timer Endpoints), Tenant (Owner), Webhook (Payment)

### Community 253 - "15. KRITERIA PENERIMAAN (ACCEPTANCE CRITERIA)"
Cohesion: 0.40
Nodes (5): 15. KRITERIA PENERIMAAN (ACCEPTANCE CRITERIA), Owner, Peserta, SuperAdmin, Timer

### Community 254 - "16. PERTIMBANGAN TEKNIS TAMBAHAN"
Cohesion: 0.40
Nodes (5): 16. PERTIMBANGAN TEKNIS TAMBAHAN, Monitoring, Performance, Skalabilitas, Timer Architecture Summary

### Community 255 - "4.3 Manajemen Kuis (Quiz Management)"
Cohesion: 0.40
Nodes (5): 4.3.1 Pembuatan Soal via AI, 4.3.2 Edit & Manajemen Soal, 4.3.3 Detail Konfigurasi Kuis, 4.3.4 Daftar Kuis, 4.3 Manajemen Kuis (Quiz Management)

### Community 257 - "14. ROADMAP & FASE PENGEMBANGAN"
Cohesion: 0.50
Nodes (4): 14. ROADMAP & FASE PENGEMBANGAN, Fase 1 — MVP (Core), Fase 2 — Lengkap, Fase 3 — Advanced

### Community 258 - "1. RINGKASAN PRODUK"
Cohesion: 0.50
Nodes (4): 1.1 Visi Produk, 1.2 Tech Stack, 1.3 Arsitektur Multi-Tenant, 1. RINGKASAN PRODUK

### Community 259 - "2. PERAN & HIERARKI PENGGUNA"
Cohesion: 0.50
Nodes (4): 2.1 SuperAdmin, 2.2 Owner, 2.3 User (Peserta / Participant), 2. PERAN & HIERARKI PENGGUNA

### Community 260 - "6. SISTEM LLM & PROMPT ENGINEERING"
Cohesion: 0.50
Nodes (4): 6.1 Prompt Template (sistem), 6.2 Provider Strategy, 6.3 Kalkulasi Token Konsumsi, 6. SISTEM LLM & PROMPT ENGINEERING

### Community 261 - "7. SISTEM TOKEN"
Cohesion: 0.50
Nodes (4): 7.1 Lifecycle Token, 7.2 Tabel Token (Central DB), 7.3 Aturan Unlimited Token, 7. SISTEM TOKEN

### Community 262 - "8. PAYMENT GATEWAY"
Cohesion: 0.50
Nodes (4): 8.1 Flow Pembelian Token, 8.2 Tabel Pembayaran (Central DB), 8.3 Driver Pattern untuk Gateway, 8. PAYMENT GATEWAY

### Community 263 - "9. NOTIFIKASI"
Cohesion: 0.50
Nodes (4): 9.1 Event Notifikasi, 9.2 Template Notifikasi, 9.3 Queue, 9. NOTIFIKASI

## Knowledge Gaps
- **299 isolated node(s):** `$schema`, `name`, `version`, `type`, `description` (+294 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **50 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Owner` connect `Owner` to `Controller`, `User`, `TokenOrder`, `Illuminate\Database\Eloquent\Relations\HasMany`?**
  _High betweenness centrality (0.043) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Illuminate\Database\Eloquent\Relations\HasMany`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Why does `LlmProvider` connect `LlmProvider` to `TokenPackage`, `Controller`, `Illuminate\Database\Eloquent\Model`?**
  _High betweenness centrality (0.017) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `version` to the rest of the system?**
  _299 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Owner` be split into smaller, more focused modules?**
  _Cohesion score 0.05704365079365079 - nodes in this community are weakly interconnected._
- **Should `LlmProvider` be split into smaller, more focused modules?**
  _Cohesion score 0.06262626262626263 - nodes in this community are weakly interconnected._
- **Should `dependencies` be split into smaller, more focused modules?**
  _Cohesion score 0.0425531914893617 - nodes in this community are weakly interconnected._