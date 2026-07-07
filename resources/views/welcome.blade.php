<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">

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
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Vite (Tailwind CSS & Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #030712;
            color: #f3f4f6;
            overflow-x: hidden;
        }

        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card {
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.6) 0%, rgba(17, 24, 39, 0.4) 100%);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.2);
        }

        .text-gradient {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 50%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-gold {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>

<body class="font-sans antialiased selection:bg-brand-500 selection:text-white">

    <!-- Background Glow Orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute top-1/3 -right-20 w-96 h-96 bg-cyber-purple/15 rounded-full blur-3xl animate-pulse-slow"
            style="animation-delay: 1.5s;"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-cyber-cyan/15 rounded-full blur-3xl animate-pulse-slow"
            style="animation-delay: 3s;"></div>
    </div>

    <!-- Navigation -->
    <header class="sticky top-0 z-50 glass-panel border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 via-cyber-purple to-cyber-cyan flex items-center justify-center shadow-lg shadow-brand-500/30">
                        <span class="text-2xl font-black text-white">M</span>
                    </div>
                    <span class="text-xl font-display font-bold tracking-tight text-white">MariLMS <span
                            class="text-gradient">AI</span></span>
                </div>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-gray-300">
                    <a href="#features" class="hover:text-white transition-colors">Fitur Unggulan</a>
                    <a href="#analytics-demo" class="hover:text-white transition-colors">Analitik Real-Time</a>
                    <a href="#pricing" class="hover:text-white transition-colors">Paket & Harga</a>
                    <a href="#trust" class="hover:text-white transition-colors">Keamanan Ujian</a>
                </nav>

                <!-- CTA Actions -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.login') }}"
                        class="text-sm font-semibold text-gray-300 hover:text-white px-4 py-2 rounded-lg transition-colors">
                        Masuk Pengajar
                    </a>
                    <a href="{{ route('owner.register') }}"
                        class="relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-semibold rounded-xl group bg-gradient-to-br from-brand-500 via-cyber-purple to-cyber-cyan group-hover:from-brand-500 group-hover:to-cyber-cyan hover:text-white text-white shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105">
                        <span
                            class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-gray-950/80 rounded-[10px] group-hover:bg-opacity-0">
                            🚀 Daftar Lembaga
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 bg-grid">

        <!-- Hero Section -->
        <section class="pt-20 pb-28 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">

            <!-- Live Status Badge -->
            <div
                class="inline-flex items-center space-x-2 px-4 py-2 rounded-full glass-card border-brand-500/30 text-xs font-semibold text-brand-300 mb-8 animate-glow">
                <span class="flex h-2 w-2 relative">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyber-cyan opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyber-cyan"></span>
                </span>
                <span>⚡ Generasi Kuis AI Generative v3.0 Aktif — Rata-rata 0.8 Detik / Soal</span>
            </div>

            <!-- Headline -->
            <h1
                class="text-4xl sm:text-6xl lg:text-7xl font-display font-extrabold tracking-tight text-white max-w-5xl mx-auto leading-tight sm:leading-none">
                Revolusi Evaluasi Akademik & <br class="hidden sm:inline">
                <span class="text-gradient">Ujian Digital Berbasis AI</span>
            </h1>

            <!-- Subheadline -->
            <p class="mt-6 text-lg sm:text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed">
                Platform SaaS multi-tenant yang memangkas pembuatan soal ujian dari jam ke detik dengan AI. Dilengkapi
                <strong class="text-gray-200">proteksi anti-cheat real-time</strong>, notifikasi WhatsApp otomatis, dan
                dasbor analitik kelulusan.
            </p>

            <!-- CTA Group -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('owner.register') }}"
                    class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-brand-600 via-brand-500 to-cyber-purple text-white font-bold text-base shadow-xl shadow-brand-500/30 hover:shadow-brand-500/50 hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-2">
                    <span>⚡ Mulai Gratis Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#analytics-demo"
                    class="w-full sm:w-auto px-8 py-4 rounded-xl glass-card text-gray-300 font-semibold text-base hover:text-white flex items-center justify-center space-x-2">
                    <span>📊 Lihat Demo Analitik</span>
                </a>
            </div>

            <!-- Hero Real-time Data Visualization Card (Glassmorphism Showcase) -->
            <div class="mt-16 relative mx-auto max-w-5xl">
                <!-- Decorative Glow Behind -->
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-brand-500 via-cyber-purple to-cyber-cyan rounded-2xl blur-xl opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200">
                </div>

                <div class="relative rounded-2xl glass-panel p-6 sm:p-8 border border-white/15 shadow-2xl text-left">

                    <!-- Dashboard Header -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-white/10 gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex space-x-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                            </div>
                            <span
                                class="text-xs font-mono text-gray-400 pl-2 border-l border-white/10">tenant-sekolah.marilms.id/dashboard/analytics</span>
                        </div>
                        <div class="flex items-center space-x-3 text-xs">
                            <span
                                class="px-2.5 py-1 rounded-md bg-brand-500/20 text-brand-300 border border-brand-500/30 flex items-center gap-1.5 font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-ping"></span> Live Evaluation
                            </span>
                            <span class="text-gray-400">Update: <strong class="text-white">Real-time</strong></span>
                        </div>
                    </div>

                    <!-- Dashboard Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 my-6">
                        <div class="glass-card p-4 rounded-xl">
                            <div class="text-xs text-gray-400 font-medium">Total Ujian Aktif</div>
                            <div class="text-2xl font-display font-bold text-white mt-1">24 Kuis</div>
                            <div class="text-xs text-cyber-emerald mt-2 flex items-center gap-1">
                                <span>↑ 18% dari minggu lalu</span>
                            </div>
                        </div>
                        <div class="glass-card p-4 rounded-xl">
                            <div class="text-xs text-gray-400 font-medium">Peserta Mengerjakan</div>
                            <div class="text-2xl font-display font-bold text-white mt-1">1,428 Siswa</div>
                            <div class="text-xs text-cyber-cyan mt-2">⏱️ Waktu Server Authoritative</div>
                        </div>
                        <div class="glass-card p-4 rounded-xl">
                            <div class="text-xs text-gray-400 font-medium">Tingkat Kelulusan (Pass Rate)</div>
                            <div class="text-2xl font-display font-bold text-cyber-emerald mt-1">88.4%</div>
                            <div class="text-xs text-gray-400 mt-2">KKM Rata-rata: 75</div>
                        </div>
                        <div class="glass-card p-4 rounded-xl border-red-500/30">
                            <div class="text-xs text-gray-400 font-medium">Anti-Cheat Diblokir</div>
                            <div class="text-2xl font-display font-bold text-red-400 mt-1">12 Pelanggaran</div>
                            <div class="text-xs text-red-300 mt-2">⚠️ Tab Switch & Blur</div>
                        </div>
                    </div>

                    <!-- Simulated Data Visualization Chart -->
                    <div class="mt-6 p-5 rounded-xl bg-gray-900/60 border border-white/5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-semibold text-white">Distribusi Skor Evaluasi & Pembahasan AI
                                </h3>
                                <p class="text-xs text-gray-400">Analisis sebaran nilai dari ujian "Ujian Akhir Semester
                                    Fisika Kuantum"</p>
                            </div>
                            <span
                                class="text-xs px-2.5 py-1 rounded bg-cyber-purple/20 text-cyber-purple border border-cyber-purple/30">
                                🤖 AI Explanations Ready
                            </span>
                        </div>

                        <!-- Bar Chart Simulation -->
                        <div class="h-44 flex items-end justify-between gap-2 pt-6 px-2">
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div
                                    class="w-full bg-gradient-to-t from-brand-900 to-brand-500 rounded-t h-[30%] relative group transition-all hover:brightness-125">
                                    <span
                                        class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] bg-gray-800 text-white px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">15%</span>
                                </div>
                                <span class="text-[11px] text-gray-400">0-50</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div
                                    class="w-full bg-gradient-to-t from-brand-800 to-brand-400 rounded-t h-[45%] relative group transition-all hover:brightness-125">
                                    <span
                                        class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] bg-gray-800 text-white px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">22%</span>
                                </div>
                                <span class="text-[11px] text-gray-400">51-65</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div
                                    class="w-full bg-gradient-to-t from-cyber-purple to-cyber-cyan rounded-t h-[75%] relative group transition-all hover:brightness-125">
                                    <span
                                        class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] bg-gray-800 text-white px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">38%</span>
                                </div>
                                <span class="text-[11px] text-gray-400">66-80</span>
                            </div>
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div
                                    class="w-full bg-gradient-to-t from-cyber-emerald/60 to-cyber-emerald rounded-t h-[95%] relative group transition-all hover:brightness-125 shadow-lg shadow-cyber-emerald/20">
                                    <span
                                        class="absolute -top-7 left-1/2 -translate-x-1/2 text-[10px] bg-gray-800 text-white px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">45%</span>
                                </div>
                                <span class="text-[11px] text-cyber-emerald font-semibold">81-100</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Badges & Social Proof -->
        <section id="trust" class="py-16 border-y border-white/5 bg-gray-950/40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-xs uppercase tracking-widest font-semibold text-gray-400 mb-8">
                    🏆 Dipercaya Oleh Institusi Pendidikan, Sekolah Terkemuka, & Universitas di Indonesia
                </p>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 items-center justify-center opacity-70">
                    <div class="glass-card py-3 px-6 rounded-xl flex items-center justify-center space-x-2">
                        <span class="text-xl">🏛️</span>
                        <span class="font-display font-bold text-sm tracking-wider">UNIV NUSANTARA</span>
                    </div>
                    <div class="glass-card py-3 px-6 rounded-xl flex items-center justify-center space-x-2">
                        <span class="text-xl">🏫</span>
                        <span class="font-display font-bold text-sm tracking-wider">SMA NEGERI 1</span>
                    </div>
                    <div class="glass-card py-3 px-6 rounded-xl flex items-center justify-center space-x-2">
                        <span class="text-xl">🚀</span>
                        <span class="font-display font-bold text-sm tracking-wider">BIMBEL MARI BELAJAR</span>
                    </div>
                    <div class="glass-card py-3 px-6 rounded-xl flex items-center justify-center space-x-2">
                        <span class="text-xl">💡</span>
                        <span class="font-display font-bold text-sm tracking-wider">EDUTECH ACADEMY</span>
                    </div>
                    <div
                        class="glass-card py-3 px-6 rounded-xl flex items-center justify-center space-x-2 col-span-2 md:col-span-1">
                        <span class="text-xl">🔬</span>
                        <span class="font-display font-bold text-sm tracking-wider">SAINS INSTITUTE</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feature Highlights with Icons -->
        <section id="features" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-xs font-bold uppercase tracking-widest text-brand-400 mb-3">Keunggulan Teknologi SaaS
                </h2>
                <p class="text-3xl sm:text-5xl font-display font-bold text-white tracking-tight">
                    Arsitektur LMS Generasi Berikutnya
                </p>
                <p class="mt-4 text-gray-400 text-base">
                    Dirancang dengan teknologi canggih untuk memberikan pengalaman evaluasi yang cepat, aman, dan tanpa
                    kendala teknis.
                </p>
            </div>

            <!-- Features 3x2 Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Feature 1 -->
                <div class="glass-card p-8 rounded-2xl relative overflow-hidden group">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-brand-500 to-cyber-purple flex items-center justify-center text-2xl mb-6 shadow-lg shadow-brand-500/20 group-hover:scale-110 transition-transform">
                        🤖
                    </div>
                    <h3 class="text-xl font-display font-bold text-white mb-3">AI Quiz Generator</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Generate puluhan soal evaluasi pilihan ganda beserta bobot nilai dan pembahasan ilmiah hanya
                        dengan memasukkan topik atau materi pelajaran dalam 30 detik.
                    </p>
                    <div class="mt-6 flex items-center text-xs font-semibold text-brand-400">
                        <span>Didukung OpenRouter & DeepSeek-V3</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card p-8 rounded-2xl relative overflow-hidden group">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center text-2xl mb-6 shadow-lg shadow-red-500/20 group-hover:scale-110 transition-transform">
                        🛡️
                    </div>
                    <h3 class="text-xl font-display font-bold text-white mb-3">Anti-Cheat Enforcement</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Perhitungan waktu server-authoritative yang tidak bisa dimanipulasi siswa, lengkap dengan
                        deteksi perpindahan tab browser yang mengumpulkan ujian paksa otomatis.
                    </p>
                    <div class="mt-6 flex items-center text-xs font-semibold text-red-400">
                        <span>Zero Cheating Guarantee</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-8 rounded-2xl relative overflow-hidden group">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyber-emerald to-teal-600 flex items-center justify-center text-2xl mb-6 shadow-lg shadow-cyber-emerald/20 group-hover:scale-110 transition-transform">
                        📱
                    </div>
                    <h3 class="text-xl font-display font-bold text-white mb-3">WhatsApp Notification Engine</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Kirim undangan login ujian, pengingat batas waktu, dan laporan hasil nilai langsung ke WhatsApp
                        siswa maupun pengajar via integrasi Fonnte dan Wablast.
                    </p>
                    <div class="mt-6 flex items-center text-xs font-semibold text-cyber-emerald">
                        <span>7 Event Notifikasi Otomatis</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card p-8 rounded-2xl relative overflow-hidden group">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyber-cyan to-blue-600 flex items-center justify-center text-2xl mb-6 shadow-lg shadow-cyber-cyan/20 group-hover:scale-110 transition-transform">
                        📊
                    </div>
                    <h3 class="text-xl font-display font-bold text-white mb-3">Analitik & Ekspor Data</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Dasbor analitik mendalam untuk memantau tingkat kelulusan, skor rata-rata, dan analisis butir
                        soal. Unduh rekap nilai lengkap ke format CSV/Excel (UTF-8 BOM).
                    </p>
                    <div class="mt-6 flex items-center text-xs font-semibold text-cyber-cyan">
                        <span>Kompatibel Microsoft Excel</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="glass-card p-8 rounded-2xl relative overflow-hidden group">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center text-2xl mb-6 shadow-lg shadow-yellow-500/20 group-hover:scale-110 transition-transform">
                        💎
                    </div>
                    <h3 class="text-xl font-display font-bold text-white mb-3">Token Economy & Top-Up</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Sistem saldo token AI transparan untuk pengajar dengan proteksi row-locking database. Top-up
                        saldo instan 24/7 melalui integrasi pembayaran Midtrans.
                    </p>
                    <div class="mt-6 flex items-center text-xs font-semibold text-yellow-400">
                        <span>Midtrans Webhook Verified</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="glass-card p-8 rounded-2xl relative overflow-hidden group">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-600 to-indigo-700 flex items-center justify-center text-2xl mb-6 shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform">
                        🏫
                    </div>
                    <h3 class="text-xl font-display font-bold text-white mb-3">Isolasi Multi-Tenant</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Setiap sekolah atau lembaga memiliki ruang kerja mandiri (path-based tenancy) yang terisolasi
                        100%. Sesuaikan nama portal, slogan, dan warna brand Anda sendiri.
                    </p>
                    <div class="mt-6 flex items-center text-xs font-semibold text-purple-400">
                        <span>Powered by Stancl/Tenancy</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>

            </div>
        </section>

        <!-- Real-Time Data Visualization Showcase (Interactive Section) -->
        <section id="analytics-demo" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-t border-white/5"
            x-data="{ tab: 'passrate' }">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-xs font-bold uppercase tracking-widest text-cyber-cyan mb-3">Live Interactive Showcase
                </h2>
                <p class="text-3xl sm:text-4xl font-display font-bold text-white">
                    Analitik Mendalam untuk Keputusan Akademik Tepat
                </p>
                <p class="mt-3 text-gray-400 text-sm">
                    Klik tab di bawah untuk melihat bagaimana MariLMS AI menyajikan data evaluasi kepada pengajar secara
                    real-time.
                </p>

                <!-- Tabs -->
                <div class="mt-8 inline-flex p-1 rounded-xl glass-card border-white/10 space-x-1">
                    <button @click="tab = 'passrate'"
                        :class="tab === 'passrate' ? 'bg-brand-600 text-white shadow-lg' : 'text-gray-400 hover:text-white'"
                        class="px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                        📈 Analisis Kelulusan
                    </button>
                    <button @click="tab = 'anticheat'"
                        :class="tab === 'anticheat' ? 'bg-red-600 text-white shadow-lg' : 'text-gray-400 hover:text-white'"
                        class="px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                        🛡️ Log Anti-Cheat
                    </button>
                    <button @click="tab = 'ai_feedback'"
                        :class="tab === 'ai_feedback' ? 'bg-cyber-purple text-white shadow-lg' : 'text-gray-400 hover:text-white'"
                        class="px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                        🤖 Pembahasan AI
                    </button>
                </div>
            </div>

            <!-- Tab Content 1: Pass Rate -->
            <div x-show="tab === 'passrate'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="glass-panel p-8 rounded-2xl border border-white/10 max-w-4xl mx-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex-1 space-y-4">
                        <span
                            class="px-3 py-1 rounded-md bg-cyber-emerald/20 text-cyber-emerald text-xs font-bold uppercase tracking-wider">Metrik
                            Akademik</span>
                        <h3 class="text-2xl font-display font-bold text-white">Seberapa Efektif Pemahaman Siswa?</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Sistem secara otomatis menghitung tingkat kelulusan berdasarkan KKM yang Anda tentukan. Jika
                            tingkat kelulusan di bawah 70%, sistem AI memberi rekomendasi pengayaan materi.
                        </p>
                        <div class="pt-2 flex items-center space-x-6">
                            <div>
                                <div class="text-3xl font-display font-black text-white">88.4%</div>
                                <div class="text-xs text-gray-400 mt-1">Rata-rata Kelulusan</div>
                            </div>
                            <div class="border-l border-white/10 pl-6">
                                <div class="text-3xl font-display font-black text-cyber-cyan">78.5</div>
                                <div class="text-xs text-gray-400 mt-1">Skor Rata-rata Kelas</div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-80 glass-card p-6 rounded-xl border border-white/10 space-y-4">
                        <div class="text-sm font-semibold text-white border-b border-white/10 pb-2">Status Peserta
                            (Total: 1,428)</div>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-cyber-emerald font-semibold">Lulus (Score >= 75)</span>
                                    <span class="text-white font-bold">1,262 Siswa</span>
                                </div>
                                <div class="w-full bg-gray-800 rounded-full h-2">
                                    <div class="bg-cyber-emerald h-2 rounded-full" style="width: 88.4%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-red-400 font-semibold">Remedial (Score < 75)</span>
                                            <span class="text-white font-bold">166 Siswa</span>
                                </div>
                                <div class="w-full bg-gray-800 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: 11.6%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content 2: Anti-Cheat -->
            <div x-show="tab === 'anticheat'" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="glass-panel p-8 rounded-2xl border border-red-500/30 max-w-4xl mx-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex-1 space-y-4">
                        <span
                            class="px-3 py-1 rounded-md bg-red-500/20 text-red-400 text-xs font-bold uppercase tracking-wider">Real-Time
                            Security</span>
                        <h3 class="text-2xl font-display font-bold text-white">Log Pelanggaran Integritas Ujian</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Setiap perpindahan tab atau tindakan meminimalkan jendela dicatat oleh sistem. Jika melebihi
                            batas toleransi yang ditetapkan pengajar, ujian langsung ditutup secara paksa.
                        </p>
                        <div class="pt-2">
                            <span
                                class="text-xs text-red-300 bg-red-950/60 px-3 py-2 rounded-lg border border-red-500/30 inline-block">
                                ⚠️ Fitur ini mengamankan ujian dari pencarian jawaban di Google saat ujian berlangsung.
                            </span>
                        </div>
                    </div>
                    <div
                        class="w-full md:w-96 glass-card p-5 rounded-xl border border-red-500/20 space-y-3 font-mono text-xs">
                        <div class="text-gray-400 border-b border-white/10 pb-2 flex justify-between">
                            <span>PESERTA</span>
                            <span>PELANGGARAN</span>
                        </div>
                        <div class="flex justify-between text-red-400">
                            <span>Ahmad Rizki (ID: #104)</span>
                            <span>Tab Switch (3x - Force)</span>
                        </div>
                        <div class="flex justify-between text-yellow-400">
                            <span>Siti Aminah (ID: #209)</span>
                            <span>Tab Switch (1x - Warn)</span>
                        </div>
                        <div class="flex justify-between text-yellow-400">
                            <span>Budi Santoso (ID: #315)</span>
                            <span>Browser Blur (2x - Warn)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content 3: AI Feedback -->
            <div x-show="tab === 'ai_feedback'" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="glass-panel p-8 rounded-2xl border border-cyber-purple/30 max-w-4xl mx-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex-1 space-y-4">
                        <span
                            class="px-3 py-1 rounded-md bg-cyber-purple/20 text-cyber-purple text-xs font-bold uppercase tracking-wider">Generative
                            AI Explanation</span>
                        <h3 class="text-2xl font-display font-bold text-white">Pembahasan Ilmiah Tanpa Halusinasi</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Bukan sekadar memberi kunci jawaban A, B, C, atau D. AI MariLMS menghasilkan penjelasan
                            ringkas mengapa jawaban tersebut tepat, membantu proses belajar mandiri siswa sehabis ujian.
                        </p>
                    </div>
                    <div class="w-full md:w-96 glass-card p-5 rounded-xl border border-cyber-purple/30 space-y-3">
                        <div class="text-xs font-semibold text-cyber-purple flex items-center gap-1.5">
                            <span>🤖 Pembahasan AI — Soal #4</span>
                        </div>
                        <p class="text-xs text-gray-300 italic leading-relaxed">
                            "Jawaban benar adalah <strong class="text-white">B (Hukum Kekekalan Energi)</strong>. Karena
                            dalam sistem tertutup, total energi mekanik (energi potensial + energi kinetik) selalu
                            konstan dan tidak dapat dimusnahkan."
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Table Section -->
        <section id="pricing" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-t border-white/5">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-xs font-bold uppercase tracking-widest text-brand-400 mb-3">Investasi Terjangkau</h2>
                <p class="text-3xl sm:text-5xl font-display font-bold text-white tracking-tight">
                    Paket Token AI & Lisensi Lembaga
                </p>
                <p class="mt-4 text-gray-400 text-base">
                    Pilih paket saldo token sesuai kebutuhan sekolah atau bimbel Anda. Tidak ada biaya bulanan
                    tersembunyi, token tidak pernah kedaluwarsa!
                </p>
            </div>

            <!-- Pricing Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                @forelse($packages as $package)
                <div
                    class="glass-card rounded-2xl p-8 flex flex-col justify-between relative {{ $loop->iteration === 2 ? 'border-2 border-brand-500 shadow-2xl shadow-brand-500/20 md:-translate-y-4' : 'border-white/10' }}">
                    @if($loop->iteration === 2)
                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-brand-600 to-cyber-purple text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">
                        ⭐ Paling Populer
                    </div>
                    @endif

                    <div>
                        <!-- Package Name -->
                        <h3 class="text-2xl font-display font-bold text-white">{{ $package->name }}</h3>
                        <p class="text-gray-400 text-xs mt-2 min-h-[36px]">{{ $package->description ?: 'Paket token AI
                            hemat untuk pembuatan soal evaluasi otomatis dan analisis nilai.' }}</p>

                        <!-- Price -->
                        <div class="mt-6 pb-6 border-b border-white/10">
                            <span class="text-4xl font-display font-extrabold text-white">Rp {{
                                number_format($package->price_idr, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-400 block mt-1">Bayar sekali beli (One-time purchase)</span>
                        </div>

                        <!-- Features List -->
                        <ul class="mt-6 space-y-4 text-sm text-gray-300">
                            <li class="flex items-center space-x-3">
                                <span
                                    class="w-5 h-5 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center font-bold text-xs">✓</span>
                                <span><strong class="text-white">{{ number_format($package->token_amount) }} Token AI</strong>
                                    Saldo Aktif</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <span
                                    class="w-5 h-5 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center font-bold text-xs">✓</span>
                                <span>~{{ number_format(floor($package->token_amount / 5)) }} Butir Soal AI Generated</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <span
                                    class="w-5 h-5 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center font-bold text-xs">✓</span>
                                <span>Akses Full Anti-Cheat Engine</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <span
                                    class="w-5 h-5 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center font-bold text-xs">✓</span>
                                <span>Integrasi WhatsApp Gateway</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <span
                                    class="w-5 h-5 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center font-bold text-xs">✓</span>
                                <span>Portal Tenant & Ekspor Laporan</span>
                            </li>
                        </ul>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-8">
                        <a href="{{ route('owner.register') }}"
                            class="w-full py-3.5 px-4 rounded-xl font-bold text-sm text-center block transition-all {{ $loop->iteration === 2 ? 'bg-gradient-to-r from-brand-600 to-cyber-purple text-white shadow-lg shadow-brand-500/30 hover:scale-[1.02]' : 'glass-panel text-gray-200 hover:text-white hover:border-brand-500/50' }}">
                            Beli Paket Sekarang 🚀
                        </a>
                    </div>
                </div>
                @empty
                <!-- Fallback Pricing if seeder/table empty -->
                <div class="glass-card rounded-2xl p-8 border border-white/10 col-span-3 text-center">
                    <p class="text-gray-400">Paket token belum dikonfigurasi oleh SuperAdmin. Silakan hubungi admin.</p>
                </div>
                @endforelse
            </div>
        </section>

        <!-- Final CTA Banner -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
            <div
                class="rounded-3xl bg-gradient-to-r from-brand-900 via-brand-800 to-cyber-purple/80 p-8 sm:p-14 text-center relative overflow-hidden shadow-2xl border border-white/20">
                <div
                    class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyber-cyan/20 rounded-full blur-3xl pointer-events-none">
                </div>
                <h2 class="text-3xl sm:text-5xl font-display font-extrabold text-white tracking-tight">
                    Siap Merevolusi Cara Lembaga Anda Mengadakan Ujian?
                </h2>
                <p class="mt-4 text-brand-100 text-base max-w-2xl mx-auto">
                    Bergabunglah dengan ratusan sekolah dan bimbel modern yang telah beralih ke evaluasi berbasis AI.
                    Daftar dalam 2 menit!
                </p>
                <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('owner.register') }}"
                        class="px-8 py-4 rounded-xl bg-white text-gray-950 font-extrabold text-base shadow-xl hover:bg-gray-100 transition-all hover:scale-105">
                        🚀 Buat Tenant Lembaga Saya
                    </a>
                    <a href="{{ route('owner.login') }}"
                        class="px-8 py-4 rounded-xl bg-black/30 text-white font-semibold text-base border border-white/20 hover:bg-black/50 transition-all">
                        Masuk Akun Pengajar
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="glass-panel border-t border-white/10 py-12 text-sm text-gray-400">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-brand-600 flex items-center justify-center font-bold text-white">M
                </div>
                <span class="font-display font-bold text-white">MariLMS AI</span>
                <span class="text-xs px-2 py-0.5 rounded bg-white/5 text-gray-400 border border-white/10">v12.0
                    Enterprise</span>
            </div>
            <div class="text-center md:text-left text-xs">
                &copy; {{ date('Y') }} MariLMS AI Platform. Dikembangkan dengan ❤️ untuk kemajuan pendidikan digital.
            </div>
            <div class="flex space-x-6 text-xs">
                <a href="#features" class="hover:text-white transition-colors">Fitur</a>
                <a href="#pricing" class="hover:text-white transition-colors">Harga</a>
                <a href="{{ route('owner.login') }}" class="hover:text-white transition-colors">Login Pengajar</a>
                <a href="{{ route('superadmin.login') }}" class="hover:text-white transition-colors">SuperAdmin</a>
            </div>
        </div>
    </footer>

</body>

</html>
