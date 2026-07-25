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

    <!-- TailAdmin Sidebar Store Initialization -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    @stack('styles')
</head>

<body class="h-full bg-gray-50 text-gray-900 font-sans antialiased selection:bg-brand-500 selection:text-white"
    x-data="{ loaded: true }"
    x-init="$store.sidebar.isExpanded = window.innerWidth >= 1280;
    const checkMobile = () => {
        if (window.innerWidth < 1280) {
            $store.sidebar.setMobileOpen(false);
            $store.sidebar.isExpanded = false;
        } else {
            $store.sidebar.isMobileOpen = false;
            $store.sidebar.isExpanded = true;
        }
    };
    window.addEventListener('resize', checkMobile);">

    @php
        $user = auth('web')->user() ?? auth('owner')->user() ?? auth('participant')->user();
        $isSuperAdmin = auth('web')->check();
        $isOwner = auth('owner')->check();
        $isParticipant = auth('participant')->check();
        $tenantSlug = tenant('slug') ?? request()->segment(1) ?? 'default';

        $roleLabel = $isSuperAdmin ? 'SuperAdmin' : ($isOwner ? 'Owner Lembaga' : 'Peserta Ujian');
        $roleBadgeColor = $isSuperAdmin ? 'bg-purple-50 text-purple-700 border-purple-200' : ($isOwner ? 'bg-brand-50 text-brand-600 border-brand-200' : 'bg-success-50 text-success-700 border-success-200');
    @endphp

    <!-- TailAdmin Page Wrapper -->
    <div class="min-h-screen xl:flex bg-gray-50">

        <!-- TailAdmin Mobile Backdrop -->
        <div :class="$store.sidebar.isMobileOpen ? 'block xl:hidden' : 'hidden'"
            @click="$store.sidebar.setMobileOpen(false)"
            class="fixed z-50 h-screen w-full bg-gray-900/50 backdrop-blur-xs">
        </div>

        <!-- ============================================================ -->
        <!-- TAILADMIN SIDEBAR COMPONENT (Light Theme bg-white) -->
        <!-- ============================================================ -->
        <aside id="sidebar"
            class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white text-gray-900 h-screen transition-all duration-300 ease-in-out z-50 border-r border-gray-200"
            :class="{
                'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
                'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'translate-x-0': $store.sidebar.isMobileOpen,
                '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
            }"
            @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
            @mouseleave="$store.sidebar.setHovered(false)">
            
            <!-- Logo Section -->
            <div class="pt-6 pb-6 flex items-center"
                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-between'">
                <a href="{{ $isSuperAdmin ? route('superadmin.dashboard') : ($isOwner ? route('tenant.owner.dashboard', ['tenant' => $tenantSlug]) : route('tenant.participant.dashboard', ['tenant' => $tenantSlug])) }}" class="flex items-center gap-3">
                    <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                        src="{{ asset('images/logo/logo.svg') }}" alt="MariLMS AI Logo" class="h-9 w-auto" />
                    <img x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                        src="{{ asset('images/logo/logo-icon.svg') }}" alt="MariLMS AI Icon" class="h-9 w-9" />
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar flex-1">
                <nav class="mb-6 space-y-6">
                    <div>
                        <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400"
                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'lg:justify-center' : 'justify-start'">
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">MENU UTAMA</span>
                        </h2>

                        <ul class="flex flex-col gap-1.5">
                            @if($isSuperAdmin)
                                <!-- SUPERADMIN MENU -->
                                <li>
                                    <a href="{{ route('superadmin.dashboard') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('superadmin.dashboard') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-chart-line w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Dashboard Central</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.owners.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('superadmin.owners.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-building w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Kelola Owner Lembaga</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.token-packages.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('superadmin.token-packages.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-box w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Paket Token AI</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.llm.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('superadmin.llm.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-robot w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Provider AI LLM</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.gateways.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('superadmin.gateways.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-sliders-h w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Gateway & Pengaturan</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.logs.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('superadmin.logs.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-history w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Log Aktivitas System</span>
                                    </a>
                                </li>

                            @elseif($isOwner)
                                <!-- OWNER MENU -->
                                <li>
                                    <a href="{{ route('tenant.owner.dashboard', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tenant.owner.dashboard') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-chart-line w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Dashboard Owner</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tenant.owner.quizzes.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-magic w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Manajemen Kuis AI</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tenant.owner.participants.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-users-gear w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Kelola Peserta Ujian</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tenant.owner.tokens') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-coins w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Saldo Token & Pembelian</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.whatsapp', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tenant.owner.whatsapp') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fab fa-whatsapp w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Notifikasi WhatsApp</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.reports', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tenant.owner.reports') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-square-poll-vertical w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Laporan & Hasil Evaluasi</span>
                                    </a>
                                </li>

                            @elseif($isParticipant)
                                <!-- PARTICIPANT MENU -->
                                <li>
                                    <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tenant.participant.dashboard') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-play-circle w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Beranda Kuis & Ujian</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.participant.history', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('tenant.participant.history') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-clock-rotate-left w-5 text-center text-base"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Riwayat & Nilai Ujian</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>
            </div>

            <!-- Footer Tenant / Role Info Card -->
            <div class="py-4 border-t border-gray-200"
                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50 border border-gray-200">
                    <div class="w-8 h-8 rounded-lg bg-brand-500 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs font-bold text-gray-900 truncate">{{ tenant('name') ?? 'MariLMS System' }}</span>
                        <span class="text-[10px] text-gray-500 truncate">ID: {{ tenant('id') ?? 'Central' }}</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ============================================================ -->
        <!-- MAIN CONTENT WRAPPER -->
        <!-- ============================================================ -->
        <div class="flex-1 transition-all duration-300 ease-in-out min-w-0 flex flex-col min-h-screen"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            
            <!-- TAILADMIN APP HEADER -->
            <header class="sticky top-0 flex w-full bg-white border-b border-gray-200 z-40">
                <div class="flex flex-col items-center justify-between grow xl:flex-row xl:px-6">
                    <div class="flex items-center justify-between w-full gap-2 px-4 py-3 border-b border-gray-200 sm:gap-4 xl:justify-normal xl:border-b-0 xl:px-0 lg:py-3.5">
                        
                        <!-- Desktop Sidebar Toggle -->
                        <button class="hidden xl:flex items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-100 hover:text-gray-800 transition"
                            @click="$store.sidebar.toggleExpanded()" aria-label="Toggle Sidebar">
                            <i class="fas fa-bars text-sm"></i>
                        </button>

                        <!-- Mobile Menu Toggle -->
                        <button class="flex xl:hidden items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-100 transition"
                            @click="$store.sidebar.toggleMobileOpen()" aria-label="Toggle Mobile Menu">
                            <i class="fas fa-bars text-sm"></i>
                        </button>

                        <!-- Page Title & Breadcrumbs -->
                        <div class="flex items-center gap-2">
                            <h1 class="text-base sm:text-lg font-bold text-gray-900 tracking-tight">
                                @yield('page-title', 'Dashboard')
                            </h1>
                        </div>

                        <!-- Right Actions & Profile Dropdown -->
                        <div class="flex items-center gap-3 ml-auto">
                            @if($isOwner)
                                <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenantSlug]) }}"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold hover:bg-amber-100 transition">
                                    <i class="fas fa-coins text-amber-500"></i>
                                    <span>Saldo Token: {{ number_format(auth('owner')->user()->tokenBalance->balance ?? 0) }}</span>
                                </a>
                            @endif

                            <!-- User Profile Dropdown (TailAdmin Profile Styling) -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                    class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-gray-100 transition border border-gray-200">
                                    <div class="w-9 h-9 rounded-lg bg-brand-500 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="hidden sm:flex flex-col text-left pr-1">
                                        <span class="text-xs font-bold text-gray-900 leading-tight truncate max-w-[130px]">
                                            {{ $user->name ?? 'User' }}
                                        </span>
                                        <span class="text-[10px] font-medium text-gray-500">
                                            {{ $roleLabel }}
                                        </span>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs hidden sm:inline-block pr-1"></i>
                                </button>

                                <div x-show="open"
                                    @click.outside="open = false"
                                    x-transition
                                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-1.5 z-50">
                                    
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ $user->name ?? 'User' }}</p>
                                        <p class="text-[11px] text-gray-500 truncate">{{ $user->email ?? '-' }}</p>
                                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $roleBadgeColor }}">
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
                                            class="w-full text-left px-4 py-2 text-xs font-semibold text-error-600 hover:bg-error-50 flex items-center gap-2 transition">
                                            <i class="fas fa-right-from-bracket"></i>
                                            <span>Keluar Akun</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <!-- Main Content Container -->
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6 space-y-6 flex-1">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="p-4 rounded-xl bg-success-50 border border-success-200 text-success-700 text-xs font-medium flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-circle-check text-success-600 text-base"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-success-500 hover:text-success-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="p-4 rounded-xl bg-error-50 border border-error-200 text-error-700 text-xs font-medium flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-circle-exclamation text-error-600 text-base"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-error-500 hover:text-error-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6 text-center text-xs text-gray-500 mt-auto">
                &copy; {{ date('Y') }} <span class="font-bold text-gray-700">MariLMS AI</span> — Multi-Tenant Learning Management System.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
