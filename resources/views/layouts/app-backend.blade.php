<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — MariLMS AI</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Vite Assets (Tailwind CSS v4 & Alpine loaded locally) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="h-full bg-slate-50 text-slate-800 font-sans antialiased selection:bg-brand-500 selection:text-white"
    x-data="{ 
        isMobileOpen: false, 
        isExpanded: true,
        toggleMobile() { this.isMobileOpen = !this.isMobileOpen },
        toggleExpanded() { this.isExpanded = !this.isExpanded }
    }">

    @php
        $user = auth('web')->user() ?? auth('owner')->user() ?? auth('participant')->user();
        $isSuperAdmin = auth('web')->check();
        $isOwner = auth('owner')->check();
        $isParticipant = auth('participant')->check();
        $tenantSlug = tenant('slug') ?? request()->segment(1) ?? 'default';

        $roleLabel = $isSuperAdmin ? 'SuperAdmin' : ($isOwner ? 'Owner Lembaga' : 'Peserta Ujian');
        $roleBadgeColor = $isSuperAdmin ? 'bg-purple-100 text-purple-700 border-purple-200' : ($isOwner ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-emerald-100 text-emerald-700 border-emerald-200');
    @endphp

    <!-- Full Layout Grid Wrapper -->
    <div class="min-h-screen flex flex-col lg:flex-row bg-slate-50">

        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="isMobileOpen" 
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            @click="isMobileOpen = false" 
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden"
            style="display: none;">
        </div>

        <!-- ============================================================ -->
        <!-- TAILADMIN BACKEND SIDEBAR -->
        <!-- ============================================================ -->
        <aside 
            :class="isMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed top-0 left-0 z-50 h-full w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:z-auto flex flex-col justify-between shrink-0 shadow-xl border-r border-slate-800">
            
            <!-- Sidebar Header & Brand -->
            <div>
                <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800/80">
                    <a href="{{ $isSuperAdmin ? route('superadmin.dashboard') : ($isOwner ? route('tenant.owner.dashboard', ['tenant' => $tenantSlug]) : route('tenant.participant.dashboard', ['tenant' => $tenantSlug])) }}" 
                        class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-brand-500/20">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-white tracking-tight text-base leading-tight">MariLMS AI</span>
                            <span class="text-[10px] font-semibold tracking-wider uppercase text-brand-400">
                                {{ $isSuperAdmin ? 'Central Admin' : ($isOwner ? 'Owner Portal' : 'Student Portal') }}
                            </span>
                        </div>
                    </a>
                    <!-- Mobile Close Button -->
                    <button @click="isMobileOpen = false" class="text-slate-400 hover:text-white lg:hidden">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Navigation Links based on Role -->
                <nav class="p-4 space-y-6">
                    <div>
                        <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 block mb-2">
                            Menu Utama
                        </span>
                        
                        <ul class="space-y-1">
                            @if($isSuperAdmin)
                                <!-- SUPERADMIN MENU -->
                                <li>
                                    <a href="{{ route('superadmin.dashboard') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.dashboard') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-chart-line w-5 text-center text-base"></i>
                                        <span>Dashboard Central</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.owners.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.owners.*') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-building w-5 text-center text-base"></i>
                                        <span>Kelola Owner Lembaga</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.token-packages.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.token-packages.*') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-box w-5 text-center text-base"></i>
                                        <span>Paket Token AI</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.llm-providers.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.llm-providers.*') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-robot w-5 text-center text-base"></i>
                                        <span>Provider AI LLM</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.gateways.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.gateways.*') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-sliders-h w-5 text-center text-base"></i>
                                        <span>Gateway & Pengaturan</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.logs.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.logs.*') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-history w-5 text-center text-base"></i>
                                        <span>Log Aktivitas System</span>
                                    </a>
                                </li>

                            @elseif($isOwner)
                                <!-- OWNER MENU -->
                                <li>
                                    <a href="{{ route('tenant.owner.dashboard', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.dashboard') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-chart-line w-5 text-center text-base"></i>
                                        <span>Dashboard Owner</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.quizzes.*') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-magic w-5 text-center text-base"></i>
                                        <span>Manajemen Kuis AI</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.participants.*') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-users-gear w-5 text-center text-base"></i>
                                        <span>Kelola Peserta Ujian</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.tokens') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-coins w-5 text-center text-base"></i>
                                        <span>Saldo Token & Pembelian</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.whatsapp', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.whatsapp') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fab fa-whatsapp w-5 text-center text-base"></i>
                                        <span>Notifikasi WhatsApp</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.reports', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.reports') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-square-poll-vertical w-5 text-center text-base"></i>
                                        <span>Laporan & Hasil Evaluasi</span>
                                    </a>
                                </li>

                            @elseif($isParticipant)
                                <!-- PARTICIPANT MENU -->
                                <li>
                                    <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.participant.dashboard') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-play-circle w-5 text-center text-base"></i>
                                        <span>Beranda Kuis & Ujian</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.participant.history', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.participant.history') ? 'bg-brand-500 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <i class="fas fa-clock-rotate-left w-5 text-center text-base"></i>
                                        <span>Riwayat & Nilai Ujian</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>
            </div>

            <!-- Sidebar Footer (Tenant Info) -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-brand-400 font-bold text-xs">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs font-semibold text-white truncate">
                            {{ tenant('name') ?? 'MariLMS System' }}
                        </span>
                        <span class="text-[10px] text-slate-400 truncate">
                            ID: {{ tenant('id') ?? 'Central' }}
                        </span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ============================================================ -->
        <!-- MAIN CONTENT AREA -->
        <!-- ============================================================ -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            
            <!-- HEADER BAR (Pure Light Mode) -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                
                <!-- Left: Sidebar Toggle & Page Title -->
                <div class="flex items-center gap-3">
                    <button @click="toggleMobile()" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-tight">
                            @yield('page-title', 'Dashboard')
                        </h2>
                    </div>
                </div>

                <!-- Right: Actions & Profile -->
                <div class="flex items-center gap-3">
                    
                    @if($isOwner)
                        <!-- Token Balance Pill for Owner -->
                        <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenantSlug]) }}" 
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold hover:bg-amber-100 transition-colors">
                            <i class="fas fa-coins text-amber-500"></i>
                            <span>Saldo Token: {{ number_format(auth('owner')->user()->tokenBalance->balance ?? 0) }}</span>
                        </a>
                    @endif

                    <!-- User Dropdown (Alpine) -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                            class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors border border-slate-200">
                            <div class="w-8 h-8 rounded-lg bg-brand-500 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="hidden sm:flex flex-col text-left">
                                <span class="text-xs font-bold text-slate-900 leading-tight truncate max-w-[120px]">
                                    {{ $user->name ?? 'User' }}
                                </span>
                                <span class="text-[10px] font-medium text-slate-500">
                                    {{ $roleLabel }}
                                </span>
                            </div>
                            <i class="fas fa-chevron-down text-slate-400 text-xs hidden sm:inline-block ml-1"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 z-50"
                            style="display: none;">
                            
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $user->name ?? 'User' }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ $user->email ?? '-' }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $roleBadgeColor }}">
                                    {{ $roleLabel }}
                                </span>
                            </div>

                            @php
                                $logoutAction = $isParticipant
                                    ? route('tenant.logout', ['tenant' => $tenantSlug])
                                    : route('logout');
                            @endphp

                            <form method="POST" action="{{ $logoutAction }}">
                                @csrf
                                <button type="submit" 
                                    class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors">
                                    <i class="fas fa-right-from-bracket"></i>
                                    <span>Keluar Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <!-- MAIN BODY CONTENT -->
            <main class="p-4 sm:p-6 lg:p-8 flex-1 space-y-6">
                
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-circle-check text-emerald-600 text-base"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-medium flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-circle-exclamation text-red-600 text-base"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <!-- Yield Page Content -->
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="bg-white border-t border-slate-200 py-4 px-6 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} <span class="font-bold text-slate-700">MariLMS AI</span> — Multi-Tenant Learning Management System.
            </footer>

        </div>

    </div>

    @stack('scripts')
</body>

</html>
