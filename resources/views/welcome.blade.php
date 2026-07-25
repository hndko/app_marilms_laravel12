<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MariLMS AI — Platform Evaluasi & Ujian Digital Berbasis AI Multi-Tenant</title>
    <meta name="description"
        content="Platform Learning Management System (LMS) SaaS modern dengan pembuatan soal otomatis berbantuan AI, proteksi anti-cheat real-time, dan analitik kelulusan.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Vite (Tailwind CSS v4 & Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-slate-900 font-sans antialiased">

    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
                    <div
                        class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white font-display font-bold text-sm">
                        M</div>
                    <span class="text-lg font-display font-bold text-slate-900 tracking-tight">MariLMS
                        <span class="text-primary-600">AI</span></span>
                </a>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-500">
                    <a href="#fitur" class="hover:text-slate-900 transition-colors duration-150">Fitur</a>
                    <a href="#cara-kerja" class="hover:text-slate-900 transition-colors duration-150">Cara Kerja</a>
                    <a href="#harga" class="hover:text-slate-900 transition-colors duration-150">Harga</a>
                </nav>

                <!-- CTA -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors duration-150">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-lg transition-colors duration-150">
                        Daftar Lembaga
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>

        {{-- ============================================================ --}}
        {{-- HERO SECTION --}}
        {{-- ============================================================ --}}
        <section class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 text-center">
                <!-- Badge -->
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-primary-200 bg-primary-50 text-primary-700 text-xs font-semibold mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                    Platform Evaluasi Akademik Berbasis AI
                </div>

                <!-- Headline -->
                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-display font-extrabold text-slate-900 tracking-tight leading-tight max-w-4xl mx-auto">
                    Buat Soal Ujian dalam Hitungan Detik,
                    <span class="text-primary-600">Bukan Berjam-jam</span>
                </h1>

                <!-- Subheadline -->
                <p class="mt-5 text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed">
                    MariLMS AI membantu pengajar membuat soal evaluasi lengkap dengan pembahasan ilmiah secara otomatis,
                    menyelenggarakan ujian dengan proteksi anti-cheat, dan menganalisis kelulusan siswa — semua dalam
                    satu platform multi-tenant.
                </p>

                <!-- CTA Group -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('register') }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm transition-colors duration-150">
                        Mulai Gratis Sekarang →
                    </a>
                    <a href="#cara-kerja"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg border border-gray-300 hover:border-gray-400 text-slate-700 font-semibold text-sm transition-colors duration-150">
                        Lihat Cara Kerja
                    </a>
                </div>

                <!-- Stats Row -->
                <div
                    class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto border-t border-gray-100 pt-10">
                    <div>
                        <p class="text-3xl font-display font-bold text-slate-900">0,8<span
                                class="text-lg text-slate-400 ml-0.5">dtk</span></p>
                        <p class="text-sm text-slate-500 mt-1">Rata-rata per soal</p>
                    </div>
                    <div>
                        <p class="text-3xl font-display font-bold text-slate-900">3</p>
                        <p class="text-sm text-slate-500 mt-1">Peran pengguna</p>
                    </div>
                    <div>
                        <p class="text-3xl font-display font-bold text-slate-900">7</p>
                        <p class="text-sm text-slate-500 mt-1">Event notifikasi WA</p>
                    </div>
                    <div>
                        <p class="text-3xl font-display font-bold text-slate-900">100%</p>
                        <p class="text-sm text-slate-500 mt-1">Isolasi data tenant</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- DASHBOARD PREVIEW --}}
        {{-- ============================================================ --}}
        <section class="bg-background py-16">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <!-- Browser Bar -->
                    <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-gray-50">
                        <div class="flex gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                        </div>
                        <div
                            class="ml-3 flex-1 max-w-sm h-6 rounded bg-gray-100 border border-gray-200 flex items-center px-3">
                            <span class="text-[11px] text-slate-400 font-mono">tenant-sekolah.marilms.id/dashboard</span>
                        </div>
                    </div>

                    <!-- Dashboard Content -->
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Ringkasan Evaluasi</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Semester Genap 2025/2026 — SMA Nusantara</p>
                            </div>
                            <span
                                class="text-xs font-medium text-primary-600 bg-primary-50 border border-primary-200 px-2.5 py-1 rounded-md">
                                Data Real-time
                            </span>
                        </div>

                        <!-- Metrics Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-xs text-slate-500 font-medium">Total Kuis Aktif</p>
                                <p class="text-2xl font-display font-bold text-slate-900 mt-1">24</p>
                                <p class="text-xs text-success mt-2">↑ 18% dari minggu lalu</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-xs text-slate-500 font-medium">Peserta Mengerjakan</p>
                                <p class="text-2xl font-display font-bold text-slate-900 mt-1">1.428</p>
                                <p class="text-xs text-slate-400 mt-2">Timer server-authoritative</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-xs text-slate-500 font-medium">Tingkat Kelulusan</p>
                                <p class="text-2xl font-display font-bold text-success mt-1">88,4%</p>
                                <p class="text-xs text-slate-400 mt-2">KKM rata-rata: 75</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="text-xs text-slate-500 font-medium">Pelanggaran Anti-Cheat</p>
                                <p class="text-2xl font-display font-bold text-danger mt-1">12</p>
                                <p class="text-xs text-slate-400 mt-2">Tab switch & browser blur</p>
                            </div>
                        </div>

                        <!-- Score Distribution -->
                        <div class="mt-6 border border-gray-200 rounded-lg p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">Distribusi Skor Evaluasi</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Ujian Akhir Semester — Fisika Kuantum</p>
                                </div>
                            </div>
                            <div class="flex items-end gap-3 h-32">
                                <div class="flex-1 flex flex-col items-center gap-1.5">
                                    <div class="w-full bg-red-100 rounded-t" style="height: 30%"></div>
                                    <span class="text-[11px] text-slate-400 font-medium">0–50</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-1.5">
                                    <div class="w-full bg-warning/20 rounded-t" style="height: 45%"></div>
                                    <span class="text-[11px] text-slate-400 font-medium">51–65</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-1.5">
                                    <div class="w-full bg-primary-100 rounded-t" style="height: 70%"></div>
                                    <span class="text-[11px] text-slate-400 font-medium">66–80</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-1.5">
                                    <div class="w-full bg-success/20 rounded-t" style="height: 95%"></div>
                                    <span class="text-[11px] text-slate-400 font-semibold text-success">81–100</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- DIPERCAYA OLEH --}}
        {{-- ============================================================ --}}
        <section class="bg-white border-y border-gray-100 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-xs uppercase tracking-widest font-semibold text-slate-400 mb-8">
                    Dipercaya oleh institusi pendidikan di seluruh Indonesia
                </p>
                <div class="flex flex-wrap items-center justify-center gap-8 md:gap-14 text-slate-300">
                    <span class="font-display font-bold text-base tracking-wider">UNIV NUSANTARA</span>
                    <span class="font-display font-bold text-base tracking-wider">SMA NEGERI 1</span>
                    <span class="font-display font-bold text-base tracking-wider">BIMBEL MARI BELAJAR</span>
                    <span class="font-display font-bold text-base tracking-wider">EDUTECH ACADEMY</span>
                    <span class="font-display font-bold text-base tracking-wider">SAINS INSTITUTE</span>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- FITUR UNGGULAN --}}
        {{-- ============================================================ --}}
        <section id="fitur" class="bg-white py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-2">Keunggulan Platform</p>
                    <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 tracking-tight">
                        Semua yang dibutuhkan untuk evaluasi akademik modern
                    </h2>
                    <p class="mt-3 text-slate-500 text-base">
                        Dirancang untuk pengajar yang menginginkan efisiensi tanpa mengorbankan integritas ujian.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div
                        class="border border-gray-200 rounded-xl p-6 hover:border-primary-300 hover:shadow-sm transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center text-lg mb-4">
                            ⚡
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-2">AI Quiz Generator</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Buat puluhan soal evaluasi pilihan ganda beserta pembahasan ilmiah hanya dengan memasukkan
                            topik materi pelajaran. Rata-rata 0,8 detik per soal.
                        </p>
                        <p class="mt-4 text-xs font-semibold text-primary-600">Didukung OpenRouter & DeepSeek →</p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="border border-gray-200 rounded-xl p-6 hover:border-primary-300 hover:shadow-sm transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-lg bg-red-50 text-danger flex items-center justify-center text-lg mb-4">
                            🔒
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-2">Anti-Cheat Enforcement</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Timer server-authoritative yang tidak bisa dimanipulasi. Deteksi perpindahan tab browser
                            dengan force-submit otomatis saat batas dilanggar.
                        </p>
                        <p class="mt-4 text-xs font-semibold text-danger">Zero cheating tolerance →</p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="border border-gray-200 rounded-xl p-6 hover:border-primary-300 hover:shadow-sm transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-lg bg-green-50 text-success flex items-center justify-center text-lg mb-4">
                            💬
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-2">Notifikasi WhatsApp</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Kirim undangan ujian, pengingat batas waktu, dan laporan hasil nilai langsung ke WhatsApp
                            siswa melalui integrasi Fonnte dan Wablast.
                        </p>
                        <p class="mt-4 text-xs font-semibold text-success">7 event notifikasi otomatis →</p>
                    </div>

                    <!-- Feature 4 -->
                    <div
                        class="border border-gray-200 rounded-xl p-6 hover:border-primary-300 hover:shadow-sm transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-lg bg-sky-50 text-info flex items-center justify-center text-lg mb-4">
                            📊
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-2">Analitik & Ekspor Data</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Pantau tingkat kelulusan, skor rata-rata, dan analisis butir soal. Unduh rekap nilai lengkap
                            ke format CSV/Excel (UTF-8 BOM).
                        </p>
                        <p class="mt-4 text-xs font-semibold text-info">Kompatibel Microsoft Excel →</p>
                    </div>

                    <!-- Feature 5 -->
                    <div
                        class="border border-gray-200 rounded-xl p-6 hover:border-primary-300 hover:shadow-sm transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-lg bg-amber-50 text-warning flex items-center justify-center text-lg mb-4">
                            💎
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-2">Token Economy & Top-Up</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Sistem saldo token AI transparan dengan proteksi row-locking database. Top-up instan 24/7
                            melalui integrasi pembayaran Midtrans.
                        </p>
                        <p class="mt-4 text-xs font-semibold text-warning">Webhook signature verified →</p>
                    </div>

                    <!-- Feature 6 -->
                    <div
                        class="border border-gray-200 rounded-xl p-6 hover:border-primary-300 hover:shadow-sm transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center text-lg mb-4">
                            🏫
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 mb-2">Isolasi Multi-Tenant</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Setiap lembaga memiliki workspace mandiri yang terisolasi 100%. Sesuaikan nama portal,
                            slogan, dan warna brand Anda sendiri.
                        </p>
                        <p class="mt-4 text-xs font-semibold text-violet-600">Powered by Stancl/Tenancy →</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- CARA KERJA --}}
        {{-- ============================================================ --}}
        <section id="cara-kerja" class="bg-background py-24 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-2">Alur Penggunaan</p>
                    <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 tracking-tight">
                        Dari pendaftaran hingga hasil evaluasi
                    </h2>
                    <p class="mt-3 text-slate-500 text-base">
                        Empat langkah sederhana untuk menyelenggarakan ujian digital berkualitas enterprise.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div
                            class="w-10 h-10 rounded-full bg-primary-600 text-white text-sm font-bold flex items-center justify-center mx-auto mb-4">
                            1</div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Daftarkan Lembaga</h3>
                        <p class="text-sm text-slate-500">Buat akun pengajar dan setup tenant lembaga Anda dalam 2
                            menit. Dapatkan portal ujian mandiri.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="text-center">
                        <div
                            class="w-10 h-10 rounded-full bg-primary-600 text-white text-sm font-bold flex items-center justify-center mx-auto mb-4">
                            2</div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Buat Kuis dengan AI</h3>
                        <p class="text-sm text-slate-500">Masukkan topik materi, AI akan menghasilkan soal lengkap
                            dengan pembahasan dan kunci jawaban.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="text-center">
                        <div
                            class="w-10 h-10 rounded-full bg-primary-600 text-white text-sm font-bold flex items-center justify-center mx-auto mb-4">
                            3</div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Undang Peserta</h3>
                        <p class="text-sm text-slate-500">Tambahkan siswa secara individual atau impor massal via CSV.
                            Kredensial login dikirim otomatis via WhatsApp.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="text-center">
                        <div
                            class="w-10 h-10 rounded-full bg-primary-600 text-white text-sm font-bold flex items-center justify-center mx-auto mb-4">
                            4</div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Analisis Hasil</h3>
                        <p class="text-sm text-slate-500">Pantau progres ujian real-time, lihat tingkat kelulusan, dan
                            unduh laporan nilai ke Excel.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- INTERACTIVE SHOWCASE (Alpine.js Tabs) --}}
        {{-- ============================================================ --}}
        <section class="bg-white py-24 border-t border-gray-100" x-data="{ tab: 'passrate' }">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-2">Demo Interaktif</p>
                    <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 tracking-tight">
                        Analitik mendalam untuk keputusan akademik
                    </h2>
                </div>

                <!-- Tabs -->
                <div class="flex justify-center mb-8">
                    <div class="inline-flex p-1 rounded-lg bg-gray-100 gap-1">
                        <button @click="tab = 'passrate'"
                            :class="tab === 'passrate' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2 rounded-md text-sm font-semibold transition-all duration-150">
                            Analisis Kelulusan
                        </button>
                        <button @click="tab = 'anticheat'"
                            :class="tab === 'anticheat' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2 rounded-md text-sm font-semibold transition-all duration-150">
                            Log Anti-Cheat
                        </button>
                        <button @click="tab = 'ai_feedback'"
                            :class="tab === 'ai_feedback' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-700'"
                            class="px-4 py-2 rounded-md text-sm font-semibold transition-all duration-150">
                            Pembahasan AI
                        </button>
                    </div>
                </div>

                <!-- Tab 1: Pass Rate -->
                <div x-show="tab === 'passrate'" x-transition.opacity.duration.150ms
                    class="border border-gray-200 rounded-xl p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row items-start justify-between gap-8">
                        <div class="flex-1 space-y-3">
                            <span
                                class="inline-block px-2.5 py-1 rounded-md bg-green-50 text-success text-xs font-bold uppercase tracking-wider">Metrik
                                Akademik</span>
                            <h3 class="text-xl font-display font-bold text-slate-900">Seberapa Efektif Pemahaman Siswa?
                            </h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Sistem otomatis menghitung tingkat kelulusan berdasarkan KKM. Jika di bawah 70%, sistem
                                memberi rekomendasi pengayaan materi.
                            </p>
                            <div class="flex items-center gap-8 pt-2">
                                <div>
                                    <p class="text-3xl font-display font-bold text-slate-900">88,4%</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Rata-rata kelulusan</p>
                                </div>
                                <div class="border-l border-gray-200 pl-8">
                                    <p class="text-3xl font-display font-bold text-primary-600">78,5</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Skor rata-rata kelas</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-72 border border-gray-200 rounded-lg p-5 space-y-4">
                            <p class="text-sm font-semibold text-slate-900 pb-2 border-b border-gray-100">Status Peserta
                                (1.428)</p>
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-success font-semibold">Lulus (≥ 75)</span>
                                    <span class="text-slate-900 font-bold">1.262</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-success h-2 rounded-full" style="width: 88.4%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-danger font-semibold">Remedial (< 75)</span>
                                    <span class="text-slate-900 font-bold">166</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-danger h-2 rounded-full" style="width: 11.6%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Anti-Cheat -->
                <div x-show="tab === 'anticheat'" x-cloak x-transition.opacity.duration.150ms
                    class="border border-gray-200 rounded-xl p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row items-start justify-between gap-8">
                        <div class="flex-1 space-y-3">
                            <span
                                class="inline-block px-2.5 py-1 rounded-md bg-red-50 text-danger text-xs font-bold uppercase tracking-wider">Keamanan
                                Real-Time</span>
                            <h3 class="text-xl font-display font-bold text-slate-900">Log Pelanggaran Integritas Ujian
                            </h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Setiap perpindahan tab atau minimalisasi jendela dicatat oleh sistem. Jika melebihi batas
                                toleransi, ujian langsung dikumpulkan secara paksa.
                            </p>
                            <div
                                class="mt-3 inline-block text-xs text-danger bg-red-50 border border-red-200 px-3 py-2 rounded-lg">
                                Fitur ini mengamankan ujian dari pencarian jawaban di mesin pencari saat ujian berlangsung.
                            </div>
                        </div>
                        <div class="w-full md:w-96 border border-gray-200 rounded-lg overflow-hidden">
                            <div
                                class="bg-gray-50 border-b border-gray-200 px-4 py-2.5 flex justify-between text-xs font-semibold text-slate-500">
                                <span>PESERTA</span>
                                <span>PELANGGARAN</span>
                            </div>
                            <div class="divide-y divide-gray-100 text-xs font-mono">
                                <div class="flex justify-between px-4 py-3 text-danger">
                                    <span>Ahmad Rizki (#104)</span>
                                    <span>Tab Switch 3x — Force Submit</span>
                                </div>
                                <div class="flex justify-between px-4 py-3 text-warning">
                                    <span>Siti Aminah (#209)</span>
                                    <span>Tab Switch 1x — Peringatan</span>
                                </div>
                                <div class="flex justify-between px-4 py-3 text-warning">
                                    <span>Budi Santoso (#315)</span>
                                    <span>Browser Blur 2x — Peringatan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: AI Feedback -->
                <div x-show="tab === 'ai_feedback'" x-cloak x-transition.opacity.duration.150ms
                    class="border border-gray-200 rounded-xl p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row items-start justify-between gap-8">
                        <div class="flex-1 space-y-3">
                            <span
                                class="inline-block px-2.5 py-1 rounded-md bg-violet-50 text-violet-600 text-xs font-bold uppercase tracking-wider">Generative
                                AI</span>
                            <h3 class="text-xl font-display font-bold text-slate-900">Pembahasan Ilmiah per Soal</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Bukan sekadar kunci jawaban A, B, C, atau D. AI menghasilkan penjelasan ringkas mengapa
                                jawaban tersebut tepat, membantu proses belajar mandiri siswa sehabis ujian.
                            </p>
                        </div>
                        <div class="w-full md:w-96 border border-gray-200 rounded-lg p-5 space-y-3">
                            <p class="text-xs font-semibold text-violet-600">Pembahasan AI — Soal #4</p>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Jawaban benar adalah <strong class="text-slate-900">B (Hukum Kekekalan Energi)</strong>.
                                Karena dalam sistem tertutup, total energi mekanik (energi potensial + energi kinetik)
                                selalu konstan dan tidak dapat dimusnahkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- PRICING --}}
        {{-- ============================================================ --}}
        <section id="harga" class="bg-background py-24 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-2">Paket & Harga</p>
                    <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 tracking-tight">
                        Investasi terjangkau untuk evaluasi berkualitas
                    </h2>
                    <p class="mt-3 text-slate-500 text-base">
                        Pilih paket saldo token sesuai kebutuhan lembaga Anda. Tidak ada biaya bulanan tersembunyi,
                        token tidak pernah kedaluwarsa.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch max-w-4xl mx-auto">
                    @forelse($packages as $package)
                    <div
                        class="bg-white border rounded-xl p-6 flex flex-col justify-between {{ $loop->iteration === 2 ? 'border-primary-500 ring-1 ring-primary-500 shadow-md md:-translate-y-2' : 'border-gray-200' }}">
                        @if($loop->iteration === 2)
                        <div class="mb-4">
                            <span
                                class="text-xs font-bold text-primary-600 bg-primary-50 border border-primary-200 px-2.5 py-1 rounded-md">
                                Paling Populer
                            </span>
                        </div>
                        @endif

                        <div>
                            <h3 class="text-lg font-display font-bold text-slate-900">{{ $package->name }}</h3>
                            <p class="text-sm text-slate-500 mt-1 min-h-[40px]">{{ $package->description ?: 'Paket
                                token AI untuk pembuatan soal evaluasi otomatis.' }}</p>

                            <div class="mt-5 pb-5 border-b border-gray-100">
                                <span class="text-3xl font-display font-extrabold text-slate-900">Rp {{
                                    number_format($package->price_idr, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-400 block mt-1">Sekali beli, tanpa langganan</span>
                            </div>

                            <ul class="mt-5 space-y-3 text-sm text-slate-600">
                                <li class="flex items-center gap-2.5">
                                    <span class="text-success text-xs">✓</span>
                                    <span><strong class="text-slate-900">{{ number_format($package->token_amount) }}
                                            Token AI</strong></span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-success text-xs">✓</span>
                                    <span>~{{ number_format(floor($package->token_amount / 5)) }} butir soal AI</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-success text-xs">✓</span>
                                    <span>Anti-cheat engine</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-success text-xs">✓</span>
                                    <span>Notifikasi WhatsApp</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-success text-xs">✓</span>
                                    <span>Ekspor laporan</span>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('register') }}"
                                class="w-full py-3 px-4 rounded-lg font-semibold text-sm text-center block transition-colors duration-150 {{ $loop->iteration === 2 ? 'bg-primary-600 hover:bg-primary-700 text-white' : 'border border-gray-300 hover:border-gray-400 text-slate-700' }}">
                                Beli Paket
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="border border-gray-200 rounded-xl p-8 col-span-3 text-center bg-white">
                        <p class="text-slate-500">Paket token belum dikonfigurasi oleh SuperAdmin.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- FINAL CTA --}}
        {{-- ============================================================ --}}
        <section class="bg-white py-20 border-t border-gray-100">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 tracking-tight">
                    Siap merevolusi cara lembaga Anda mengadakan ujian?
                </h2>
                <p class="mt-4 text-slate-500 text-base max-w-xl mx-auto">
                    Bergabunglah dengan institusi pendidikan yang telah beralih ke evaluasi berbasis AI. Daftar dalam 2
                    menit, tanpa kartu kredit.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-3">
                    <a href="{{ route('register') }}"
                        class="px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm transition-colors duration-150">
                        Daftar Lembaga Saya →
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-6 py-3 rounded-lg border border-gray-300 hover:border-gray-400 text-slate-700 font-semibold text-sm transition-colors duration-150">
                        Masuk Akun Pengajar
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white py-10">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div
                    class="w-6 h-6 rounded bg-primary-600 flex items-center justify-center text-white text-xs font-bold">
                    M</div>
                <span class="font-display font-bold text-sm text-slate-900">MariLMS AI</span>
            </div>
            <p class="text-xs text-slate-400 text-center">
                &copy; {{ date('Y') }} MariLMS AI v{{ config('app.version', '1.4.1') }}. Dikembangkan untuk kemajuan pendidikan digital Indonesia.
            </p>
            <div class="flex gap-6 text-xs text-slate-500">
                <a href="#fitur" class="hover:text-slate-900 transition-colors duration-150">Fitur</a>
                <a href="#harga" class="hover:text-slate-900 transition-colors duration-150">Harga</a>
                <a href="{{ route('login') }}"
                    class="hover:text-slate-900 transition-colors duration-150">Masuk Portal</a>
            </div>
        </div>
    </footer>

</body>

</html>
