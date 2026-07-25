<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MariLMS AI — Platform Evaluasi & Ujian Digital Berbasis AI Multi-Tenant</title>
    <meta name="description"
        content="Platform Learning Management System (LMS) SaaS modern dengan pembuatan soal otomatis berbantuan AI, proteksi anti-cheat real-time, dan analitik kelulusan.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite (Tailwind CSS v4 & Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased">

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-sm group-hover:bg-blue-700 transition-colors">
                        <i class="fas fa-graduation-cap text-base"></i>
                    </div>
                    <span class="text-xl font-display font-extrabold text-slate-900 tracking-tight">MariLMS
                        <span class="text-blue-600">AI</span>
                    </span>
                </a>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="#fitur" class="hover:text-blue-600 transition-colors duration-150">
                        <i class="fas fa-cubes text-slate-400 mr-1.5"></i>Fitur Utama
                    </a>
                    <a href="#cara-kerja" class="hover:text-blue-600 transition-colors duration-150">
                        <i class="fas fa-route text-slate-400 mr-1.5"></i>Cara Kerja
                    </a>
                    <a href="#anti-cheat" class="hover:text-blue-600 transition-colors duration-150">
                        <i class="fas fa-shield-halved text-slate-400 mr-1.5"></i>Anti-Cheat
                    </a>
                    <a href="#harga" class="hover:text-blue-600 transition-colors duration-150">
                        <i class="fas fa-tags text-slate-400 mr-1.5"></i>Paket Token
                    </a>
                </nav>

                <!-- Action CTA -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-slate-100 transition-colors duration-150">
                        <i class="fas fa-right-to-bracket text-slate-500"></i>
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg shadow-sm transition-colors duration-150">
                        <i class="fas fa-building-user"></i>
                        Daftar Lembaga
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>

        {{-- ============================================================ --}}
        {{-- HERO SECTION (Proportional Typography & Real Tenant URL) --}}
        {{-- ============================================================ --}}
        <section class="bg-white border-b border-slate-200 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    
                    <!-- Left Column: Content -->
                    <div class="lg:col-span-7 space-y-5 text-left">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-700 text-xs font-bold">
                            <i class="fas fa-sparkles text-blue-600"></i>
                            <span>Platform Evaluasi & LMS Multi-Tenant Berbasis AI</span>
                        </div>

                        <!-- Headline (Optimized Typography: text-3xl sm:text-4xl lg:text-5xl) -->
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-slate-900 tracking-tight leading-[1.15]">
                            Buat Soal Ujian Otomatis dengan AI,
                            <span class="text-blue-600 block mt-1">Ujian Aman Anti-Kecurangan</span>
                        </h1>

                        <!-- Subheadline (Optimized font-size & line-height) -->
                        <p class="text-base sm:text-lg text-slate-600 max-w-2xl leading-relaxed">
                            MariLMS AI membantu instansi dan pengajar menghasilkan kuis berkualitas lengkap dengan kunci jawaban & pembahasan ilmiah dalam hitungan detik, didukung timer server otoritatif dan pengawasan ujian real-time.
                        </p>

                        <!-- Highlights Pills -->
                        <div class="flex flex-wrap gap-y-2 gap-x-4 text-xs font-semibold text-slate-700 pt-1">
                            <span class="inline-flex items-center gap-1.5"><i class="fas fa-circle-check text-emerald-600"></i> Generasi Soal &lt; 30 Detik</span>
                            <span class="inline-flex items-center gap-1.5"><i class="fas fa-circle-check text-emerald-600"></i> Timer Server Otoritatif</span>
                            <span class="inline-flex items-center gap-1.5"><i class="fas fa-circle-check text-emerald-600"></i> Workspace Tenant Mandiri</span>
                        </div>

                        <!-- CTA Group -->
                        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md transition-all duration-150">
                                <i class="fas fa-rocket"></i>
                                Mulai Gratis & Klaim 50 Token
                            </a>
                            <a href="#cara-kerja"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border border-slate-300 hover:border-slate-400 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm transition-all duration-150">
                                <i class="fas fa-circle-play text-slate-500"></i>
                                Lihat Alur Kerja
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Interactive Image Preview Showcase -->
                    <div class="lg:col-span-5 relative">
                        <div class="relative mx-auto max-w-md lg:max-w-none">
                            
                            <!-- Main Preview Image Card -->
                            <div class="bg-slate-100 p-2 sm:p-3 rounded-2xl border border-slate-200 shadow-xl overflow-hidden group">
                                <div class="bg-slate-200/80 px-3 py-2 rounded-xl mb-2 flex items-center justify-between text-xs text-slate-600 border border-slate-300/50">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                        <!-- Real Path-Based Tenant URL -->
                                        <span class="ml-2 font-mono text-[11px] text-slate-600 font-semibold">marilms.id/sma-nusantara/dashboard/quizzes</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">ONLINE</span>
                                </div>

                                <img src="{{ asset('images/hero_lms_preview.jpg') }}" 
                                     alt="MariLMS AI Dashboard Preview" 
                                     class="w-full h-auto object-cover rounded-xl border border-slate-200 transition-transform duration-300 group-hover:scale-[1.01]" />
                            </div>

                            <!-- Floating Badge 1: AI Status -->
                            <div class="absolute -bottom-4 -left-4 bg-white border border-slate-200 p-3 rounded-xl shadow-lg flex items-center gap-3 hidden sm:flex">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-base font-bold">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">AI Generator Ready</p>
                                    <p class="text-[11px] text-slate-500">OpenRouter & DeepSeek API</p>
                                </div>
                            </div>

                            <!-- Floating Badge 2: Anti-Cheat Status -->
                            <div class="absolute -top-4 -right-4 bg-white border border-slate-200 p-3 rounded-xl shadow-lg flex items-center gap-3 hidden sm:flex">
                                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-base font-bold">
                                    <i class="fas fa-shield-check"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">Anti-Cheat Active</p>
                                    <p class="text-[11px] text-slate-500">Authoritative Server Timer</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Stats Row -->
                <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto border-t border-slate-200 pt-8">
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                        <p class="text-3xl font-display font-extrabold text-slate-900">&lt; 30<span class="text-base font-semibold text-blue-600 ml-1">Detik</span></p>
                        <p class="text-xs font-medium text-slate-600 mt-1"><i class="fas fa-bolt text-amber-500 mr-1"></i>Generate Kuis AI</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                        <p class="text-3xl font-display font-extrabold text-slate-900">100%</p>
                        <p class="text-xs font-medium text-slate-600 mt-1"><i class="fas fa-clock text-blue-500 mr-1"></i>Timer Otoritatif Server</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                        <p class="text-3xl font-display font-extrabold text-slate-900">3-Tier</p>
                        <p class="text-xs font-medium text-slate-600 mt-1"><i class="fas fa-users-gear text-emerald-500 mr-1"></i>Role Auth Terpisah</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                        <p class="text-3xl font-display font-extrabold text-slate-900">Single-DB</p>
                        <p class="text-xs font-medium text-slate-600 mt-1"><i class="fas fa-database text-violet-500 mr-1"></i>Isolasi Tenant Scope</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- DASHBOARD PREVIEW SHOWCASE --}}
        {{-- ============================================================ --}}
        <section class="bg-slate-100/70 py-16 border-b border-slate-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-xl border border-slate-200 bg-white shadow-md overflow-hidden">
                    <!-- Browser Window Header -->
                    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                            </div>
                            <div class="ml-4 h-7 rounded-lg bg-white border border-slate-200 flex items-center px-3 gap-2">
                                <i class="fas fa-lock text-[10px] text-emerald-600"></i>
                                <span class="text-xs text-slate-600 font-mono">marilms.id/sma-nusantara/dashboard</span>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-md">
                            <i class="fas fa-shield-check mr-1"></i>Portal Tenant Aktif
                        </span>
                    </div>

                    <!-- Dashboard Body Mockup -->
                    <div class="p-6 sm:p-8 bg-white">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fas fa-chart-line text-blue-600"></i>
                                    Ringkasan Evaluasi Ujian Tenant
                                </h3>
                                <p class="text-xs text-slate-500 mt-1">Workspace: SMA Nusantara Jakarta — Tahun Ajaran 2025/2026</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Sistem Anti-Cheat Aktif
                                </span>
                            </div>
                        </div>

                        <!-- Metrics Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                                <div class="flex items-center justify-between text-slate-500 mb-2">
                                    <span class="text-xs font-semibold">Total Kuis</span>
                                    <i class="fas fa-file-signature text-blue-600"></i>
                                </div>
                                <p class="text-2xl font-display font-extrabold text-slate-900">32</p>
                                <p class="text-[11px] text-emerald-600 font-semibold mt-1"><i class="fas fa-arrow-up mr-1"></i>Generated via AI</p>
                            </div>

                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                                <div class="flex items-center justify-between text-slate-500 mb-2">
                                    <span class="text-xs font-semibold">Peserta Terdaftar</span>
                                    <i class="fas fa-users text-indigo-600"></i>
                                </div>
                                <p class="text-2xl font-display font-extrabold text-slate-900">1.240</p>
                                <p class="text-[11px] text-slate-500 mt-1"><i class="fas fa-id-card mr-1"></i>Imported via CSV</p>
                            </div>

                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                                <div class="flex items-center justify-between text-slate-500 mb-2">
                                    <span class="text-xs font-semibold">Tingkat Kelulusan</span>
                                    <i class="fas fa-circle-check text-emerald-600"></i>
                                </div>
                                <p class="text-2xl font-display font-extrabold text-emerald-600">89,2%</p>
                                <p class="text-[11px] text-slate-500 mt-1"><i class="fas fa-chart-bar mr-1"></i>KKM Rata-rata 75</p>
                            </div>

                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                                <div class="flex items-center justify-between text-slate-500 mb-2">
                                    <span class="text-xs font-semibold">Pelanggaran Terdeteksi</span>
                                    <i class="fas fa-triangle-exclamation text-amber-600"></i>
                                </div>
                                <p class="text-2xl font-display font-extrabold text-amber-600">4</p>
                                <p class="text-[11px] text-red-600 font-semibold mt-1"><i class="fas fa-ban mr-1"></i>Force Submit Triggered</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- TRUST BADGES --}}
        {{-- ============================================================ --}}
        <section class="bg-white border-b border-slate-200 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-xs uppercase tracking-widest font-bold text-slate-500 mb-6">
                    Solusi Terpercaya untuk Institusi Pendidikan & Bimbingan Belajar
                </p>
                <div class="flex flex-wrap items-center justify-center gap-8 md:gap-14 text-slate-400 text-sm font-semibold">
                    <span class="flex items-center gap-2"><i class="fas fa-university text-slate-500"></i> UNIVERSITAS NUSANTARA</span>
                    <span class="flex items-center gap-2"><i class="fas fa-school text-slate-500"></i> SMA NEGERI 1</span>
                    <span class="flex items-center gap-2"><i class="fas fa-book-open text-slate-500"></i> BIMBEL MARI BELAJAR</span>
                    <span class="flex items-center gap-2"><i class="fas fa-laptop-code text-slate-500"></i> EDUTECH ACADEMY</span>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- FITUR UNGGULAN --}}
        {{-- ============================================================ --}}
        <section id="fitur" class="bg-white py-20 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-md mb-3">
                        <i class="fas fa-star"></i> Fitur Unggulan Platform
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-slate-900 tracking-tight">
                        Segala Kebutuhan Evaluasi Akademik dalam Satu Tempat
                    </h2>
                    <p class="mt-3 text-slate-600 text-base leading-relaxed">
                        Dirancang khusus untuk menghadirkan efisiensi pembuatan soal berbantuan AI tanpa mengorbankan integritas dan keamanan ujian.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-md transition-all duration-150 flex flex-col justify-between">
                        <div>
                            <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-4">
                                <i class="fas fa-wand-magic-sparkles"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">AI Quiz Generator</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Hasilkan puluhan butir soal pilihan ganda lengkap dengan bobot nilai, kunci jawaban, dan pembahasan ilmiah secara otomatis dari topik materi pelajaran.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-blue-600">
                            <span>OpenRouter & DeepSeek API</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-md transition-all duration-150 flex flex-col justify-between">
                        <div>
                            <div class="w-11 h-11 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-xl mb-4">
                                <i class="fas fa-shield-cat"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Anti-Cheat Enforcement</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Timer otoritatif server yang presisi, dipadukan dengan deteksi perpindahan tab browser (*tab-switch*) dan *force-submit* otomatis jika batas dilanggar.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-red-600">
                            <span>Server-Authoritative Timer</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-md transition-all duration-150 flex flex-col justify-between">
                        <div>
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-4">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">WhatsApp & Email Gateway</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Notifikasi pengiriman kredensial peserta, jadwal ujian, serta laporan hasil kelulusan secara otomatis via Fonnte / Wablast WhatsApp Gateway.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-emerald-600">
                            <span>7 Event Notifikasi Otomatis</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-md transition-all duration-150 flex flex-col justify-between">
                        <div>
                            <div class="w-11 h-11 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-xl mb-4">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Analitik & Ekspor Laporan</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Pantau statistik kelulusan siswa, KKM, dan distribusi skor secara visual. Ekspor laporan hasil ujian ke format CSV / Excel secara instan.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-sky-600">
                            <span>Kompatibel Microsoft Excel</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>

                    <!-- Feature 5 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-md transition-all duration-150 flex flex-col justify-between">
                        <div>
                            <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl mb-4">
                                <i class="fas fa-coins"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Sistem Saldo Token AI</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Pengelolaan token AI transparan untuk Owner dengan proteksi *row-locking* database. Top-up token kapan saja melalui Payment Gateway Midtrans.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-amber-600">
                            <span>Atomic Transaction Engine</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>

                    <!-- Feature 6 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-md transition-all duration-150 flex flex-col justify-between">
                        <div>
                            <div class="w-11 h-11 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center text-xl mb-4">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Path Multi-Tenancy</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Setiap lembaga memperoleh URL portal mandiri (`/{tenant}/...`) dengan isolasi data terjamin berbasis `TenantScope` dalam Single Database.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-200 flex items-center justify-between text-xs font-semibold text-violet-600">
                            <span>Isolasi Data Terjamin</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- CARA KERJA --}}
        {{-- ============================================================ --}}
        <section id="cara-kerja" class="bg-slate-50 py-20 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-md mb-3">
                        <i class="fas fa-list-check"></i> Alur Penggunaan
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-slate-900 tracking-tight">
                        Cara Kerja Platform MariLMS AI
                    </h2>
                    <p class="mt-3 text-slate-600 text-base">
                        4 langkah praktis menyelenggarakan ujian daring berbasis AI di lembaga Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Step 1 -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm text-center relative">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center mx-auto mb-4 text-base shadow-sm">
                            1
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">Registrasi Tenant</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Daftarkan lembaga Anda untuk memperoleh workspace portal ujian khusus (`/{tenant}`).
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm text-center relative">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center mx-auto mb-4 text-base shadow-sm">
                            2
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">Generate Soal AI</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Masukkan materi pelajaran, pilih tingkat kesulitan, lalu biarkan AI membuat soal & pembahasan.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm text-center relative">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center mx-auto mb-4 text-base shadow-sm">
                            3
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">Undang Peserta Ujian</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Tambahkan peserta secara manual atau impor dari file CSV. Kredensial terkirim otomatis.
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm text-center relative">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center mx-auto mb-4 text-base shadow-sm">
                            4
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-2">Ujian & Analisis Hasil</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Peserta mengerjakan ujian dengan pengawasan anti-cheat. Skor & rekapitulasi langsung tersedia.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- ANTI-CHEAT SHOWCASE --}}
        {{-- ============================================================ --}}
        <section id="anti-cheat" class="bg-white py-20 border-b border-slate-200" x-data="{ tab: 'anticheat' }">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-10">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-red-700 bg-red-50 border border-red-200 px-3 py-1 rounded-md mb-3">
                        <i class="fas fa-shield-halved"></i> Fitur Integritas Ujian
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-slate-900 tracking-tight">
                        Demo Sistem Pengawasan Ujian Real-Time
                    </h2>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex justify-center mb-8">
                    <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200 gap-1">
                        <button @click="tab = 'anticheat'"
                            :class="tab === 'anticheat' ? 'bg-white shadow-sm text-blue-600 font-bold' : 'text-slate-600 hover:text-slate-900'"
                            class="px-4 py-2 rounded-lg text-xs transition-all duration-150 flex items-center gap-2">
                            <i class="fas fa-eye"></i> Log Anti-Cheat
                        </button>
                        <button @click="tab = 'timer'"
                            :class="tab === 'timer' ? 'bg-white shadow-sm text-blue-600 font-bold' : 'text-slate-600 hover:text-slate-900'"
                            class="px-4 py-2 rounded-lg text-xs transition-all duration-150 flex items-center gap-2">
                            <i class="fas fa-stopwatch"></i> Timer Server
                        </button>
                        <button @click="tab = 'ai_explanation'"
                            :class="tab === 'ai_explanation' ? 'bg-white shadow-sm text-blue-600 font-bold' : 'text-slate-600 hover:text-slate-900'"
                            class="px-4 py-2 rounded-lg text-xs transition-all duration-150 flex items-center gap-2">
                            <i class="fas fa-brain"></i> Pembahasan AI
                        </button>
                    </div>
                </div>

                <!-- Tab 1: Anti-Cheat -->
                <div x-show="tab === 'anticheat'" class="border border-slate-200 rounded-xl p-6 bg-slate-50">
                    <div class="flex flex-col md:flex-row items-start justify-between gap-6">
                        <div class="flex-1 space-y-3">
                            <span class="inline-block px-2.5 py-1 rounded-md bg-red-100 text-red-700 text-xs font-bold uppercase">
                                <i class="fas fa-triangle-exclamation mr-1"></i>Deteksi Pelanggaran
                            </span>
                            <h3 class="text-xl font-bold text-slate-900">Deteksi Tab Switch & Browser Blur</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Ketika peserta berpindah jendela atau membuka tab lain untuk mencari jawaban, sistem mencatat riwayat kejadian secara otomatis dan memicu pengumpulan paksa (*force submit*).
                            </p>
                        </div>
                        <div class="w-full md:w-96 border border-slate-200 rounded-xl bg-white overflow-hidden shadow-sm">
                            <div class="bg-slate-100 border-b border-slate-200 px-4 py-2.5 flex justify-between text-xs font-bold text-slate-700">
                                <span>PESERTA</span>
                                <span>STATUS PERINGATAN</span>
                            </div>
                            <div class="divide-y divide-slate-100 text-xs font-mono">
                                <div class="flex justify-between px-4 py-3 text-red-600 bg-red-50/50">
                                    <span>Ahmad Rizki (#104)</span>
                                    <span class="font-bold">Tab Switch 3x (Force Submit)</span>
                                </div>
                                <div class="flex justify-between px-4 py-3 text-amber-600">
                                    <span>Siti Aminah (#209)</span>
                                    <span>Tab Switch 1x (Peringatan)</span>
                                </div>
                                <div class="flex justify-between px-4 py-3 text-slate-600">
                                    <span>Budi Santoso (#315)</span>
                                    <span>Normal (0 Pelanggaran)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Timer -->
                <div x-show="tab === 'timer'" x-cloak class="border border-slate-200 rounded-xl p-6 bg-slate-50">
                    <div class="flex flex-col md:flex-row items-start justify-between gap-6">
                        <div class="flex-1 space-y-3">
                            <span class="inline-block px-2.5 py-1 rounded-md bg-blue-100 text-blue-700 text-xs font-bold uppercase">
                                <i class="fas fa-clock mr-1"></i>Otoritas Server
                            </span>
                            <h3 class="text-xl font-bold text-slate-900">Perhitungan Waktu Terpusat</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Sisa waktu ujian dihitung murni dari selisih timestamp server (`started_at` + `duration`). Mengubah jam lokal atau me-refresh browser tidak akan pernah memanipulasi sisa waktu.
                            </p>
                        </div>
                        <div class="w-full md:w-80 border border-slate-200 rounded-xl bg-white p-5 text-center shadow-sm">
                            <p class="text-xs font-semibold text-slate-500">SISA WAKTU UJIAN SERVER</p>
                            <p class="text-4xl font-mono font-extrabold text-blue-600 my-2">00:42:15</p>
                            <p class="text-[11px] text-emerald-600 font-semibold"><i class="fas fa-check-circle mr-1"></i>Sinkronisasi Otomatis Server</p>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: AI Explanation -->
                <div x-show="tab === 'ai_explanation'" x-cloak class="border border-slate-200 rounded-xl p-6 bg-slate-50">
                    <div class="flex flex-col md:flex-row items-start justify-between gap-6">
                        <div class="flex-1 space-y-3">
                            <span class="inline-block px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700 text-xs font-bold uppercase">
                                <i class="fas fa-graduation-cap mr-1"></i>Pembahasan Ilmiah
                            </span>
                            <h3 class="text-xl font-bold text-slate-900">Penjelasan Pembahasan Otomatis AI</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Setelah ujian selesai, peserta dapat langsung membaca penjelasan rinci mengapa suatu jawaban dianggap tepat untuk mendukung proses belajar mandiri.
                            </p>
                        </div>
                        <div class="w-full md:w-96 border border-slate-200 rounded-xl bg-white p-4 shadow-sm text-xs space-y-2">
                            <div class="flex items-center gap-2 text-emerald-700 font-bold">
                                <i class="fas fa-circle-check"></i> Pembahasan AI (Soal #5)
                            </div>
                            <p class="text-slate-700 leading-relaxed">
                                Jawaban benar adalah <strong>B (Hukum Kekekalan Energi)</strong>. Energi tidak dapat diciptakan atau dimusnahkan, hanya dapat berubah bentuk dari satu energi ke bentuk lainnya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- HARGA / PAKET TOKEN --}}
        {{-- ============================================================ --}}
        <section id="harga" class="bg-slate-50 py-20 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-md mb-3">
                        <i class="fas fa-tags"></i> Paket Token AI
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-slate-900 tracking-tight">
                        Investasi Terjangkau untuk Pembuatan Kuis AI
                    </h2>
                    <p class="mt-3 text-slate-600 text-base">
                        Sekali beli, token aktif selamanya tanpa biaya langganan bulanan tersembunyi.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch max-w-5xl mx-auto">
                    @forelse($packages as $package)
                    <div class="bg-white border rounded-xl p-6 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-150 {{ $loop->iteration === 2 ? 'border-blue-600 ring-2 ring-blue-600/20 md:-translate-y-2' : 'border-slate-200' }}">
                        <div>
                            @if($loop->iteration === 2)
                            <div class="mb-4">
                                <span class="text-xs font-bold text-white bg-blue-600 px-3 py-1 rounded-full">
                                    <i class="fas fa-fire mr-1"></i>Paling Populer
                                </span>
                            </div>
                            @endif

                            <h3 class="text-xl font-bold text-slate-900">{{ $package->name }}</h3>
                            <p class="text-xs text-slate-500 mt-1 min-h-[36px]">{{ $package->description ?: 'Paket saldo token AI untuk pembuatan kuis & pembahasan otomatis.' }}</p>

                            <div class="mt-5 pb-5 border-b border-slate-100">
                                <span class="text-3xl font-display font-extrabold text-slate-900">Rp {{ number_format($package->price_idr, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-400 block mt-1"><i class="fas fa-shield-check text-emerald-500 mr-1"></i>Sekali Bayar — Tanpa Langganan</span>
                            </div>

                            <ul class="mt-5 space-y-3 text-xs text-slate-700 font-medium">
                                <li class="flex items-center gap-2.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                    <span><strong class="text-slate-900">{{ number_format($package->token_amount) }} Token AI</strong></span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                    <span>Est. {{ number_format(floor($package->token_amount / 5)) }} Soal Kuis AI</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                    <span>Proteksi Anti-Cheat Server Timer</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                    <span>Gateway Notifikasi WhatsApp</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                    <span>Ekspor Rekap Nilai ke Excel</span>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ route('register') }}"
                                class="w-full py-3 px-4 rounded-xl font-bold text-xs text-center inline-flex items-center justify-center gap-2 transition-all duration-150 {{ $loop->iteration === 2 ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-800' }}">
                                <i class="fas fa-cart-shopping"></i>
                                Beli Paket Sekarang
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="border border-slate-200 rounded-xl p-8 col-span-3 text-center bg-white">
                        <p class="text-sm font-semibold text-slate-500"><i class="fas fa-info-circle mr-1"></i>Paket token belum dikonfigurasi oleh SuperAdmin.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- FINAL CTA SECTION --}}
        {{-- ============================================================ --}}
        <section class="bg-white py-20 border-b border-slate-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mx-auto mb-5 shadow-sm">
                    <i class="fas fa-school-flag"></i>
                </div>
                <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-slate-900 tracking-tight">
                    Siap Meningkatkan Efisiensi Evaluasi Ujian Lembaga Anda?
                </h2>
                <p class="mt-4 text-slate-600 text-base max-w-2xl mx-auto leading-relaxed">
                    Dapatkan portal ujian mandiri berbasis AI dan sistem anti-cheat sekarang juga. Proses pendaftaran gratis hanya membutuhkan waktu kurang dari 2 menit.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-3">
                    <a href="{{ route('register') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors duration-150">
                        <i class="fas fa-user-plus"></i>
                        Daftar Lembaga Baru
                    </a>
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border border-slate-300 hover:border-slate-400 bg-white text-slate-700 font-semibold text-sm transition-colors duration-150">
                        <i class="fas fa-right-to-bracket"></i>
                        Masuk Akun Pengajar
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Enterprise Footer -->
    <footer class="bg-slate-900 text-slate-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="font-display font-bold text-lg text-white tracking-tight">MariLMS <span class="text-blue-500">AI</span></span>
                </div>
                <div class="flex flex-wrap justify-center gap-6 text-xs text-slate-400 font-medium">
                    <a href="#fitur" class="hover:text-white transition-colors">Fitur Utama</a>
                    <a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a>
                    <a href="#anti-cheat" class="hover:text-white transition-colors">Sistem Anti-Cheat</a>
                    <a href="#harga" class="hover:text-white transition-colors">Paket Token</a>
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal Login</a>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} MariLMS AI v{{ config('app.version', '1.4.2') }}. Seluruh hak cipta dilindungi undang-undang.</p>
                <p class="flex items-center gap-1.5">
                    <i class="fas fa-shield-halved text-emerald-500"></i> Single-Database Tenancy Platform
                </p>
            </div>
        </div>
    </footer>

</body>

</html>
