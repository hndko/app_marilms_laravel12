<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — MariLMS AI Platform</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Vite Assets (Tailwind CSS v4 & Alpine loaded locally) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- MariLMS Sidebar Store Initialization -->
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

        $roleLabel = $isSuperAdmin ? 'SuperAdmin Central' : ($isOwner ? 'Owner Lembaga' : 'Peserta Ujian');
        $roleBadgeColor = $isSuperAdmin ? 'bg-purple-50 text-purple-700 border-purple-200' : ($isOwner ? 'bg-brand-50 text-brand-600 border-brand-200' : 'bg-success-50 text-success-700 border-success-200');

        $profileRoute = $isSuperAdmin 
            ? route('superadmin.profile.edit') 
            : ($isOwner 
                ? route('tenant.owner.profile.edit', ['tenant' => $tenantSlug]) 
                : route('tenant.participant.profile.edit', ['tenant' => $tenantSlug]));
    @endphp

    <!-- App Wrapper -->
    <div class="min-h-screen xl:flex bg-gray-50">

        <!-- Mobile Backdrop -->
        <div :class="$store.sidebar.isMobileOpen ? 'block xl:hidden' : 'hidden'"
            @click="$store.sidebar.setMobileOpen(false)"
            class="fixed z-50 h-screen w-full bg-gray-900/50 backdrop-blur-xs">
        </div>

        <!-- ============================================================ -->
        <!-- TAILADMIN EXACT SIDEBAR COMPONENT (Light Theme bg-white) -->
        <!-- ============================================================ -->
        <aside id="sidebar"
            class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white text-gray-900 h-screen transition-all duration-300 ease-in-out z-50 border-r border-gray-200 shadow-xs"
            :class="{
                'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
                'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'translate-x-0': $store.sidebar.isMobileOpen,
                '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
            }"
            @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
            @mouseleave="$store.sidebar.setHovered(false)">
            
            <!-- Logo Section -->
            <div class="pt-7 pb-7 flex items-center border-b border-gray-100"
                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-between'">
                <a href="{{ $isSuperAdmin ? route('superadmin.dashboard') : ($isOwner ? route('tenant.owner.dashboard', ['tenant' => $tenantSlug]) : route('tenant.participant.dashboard', ['tenant' => $tenantSlug])) }}" class="flex items-center gap-3">
                    <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                        src="{{ asset('images/logo/logo.svg') }}" alt="MariLMS AI Logo" class="h-9 w-auto" />
                    <img x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                        src="{{ asset('images/logo/logo-icon.svg') }}" alt="MariLMS AI Icon" class="h-9 w-9" />
                </a>
            </div>

            <!-- Navigation Menu with TailAdmin Grouping -->
            <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar flex-1 py-5">
                <nav class="space-y-6">
                    @if($isSuperAdmin)
                        <!-- ================= SUPERADMIN MENU ================= -->
                        <div>
                            <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400 px-3"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'text-center' : 'text-left'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">MENU UTAMA</span>
                                <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">•</span>
                            </h2>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('superadmin.dashboard') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.dashboard') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-chart-pie w-5 text-center text-base {{ request()->routeIs('superadmin.dashboard') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Dashboard Central</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400 px-3"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'text-center' : 'text-left'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">MANAJEMEN CENTRAL</span>
                                <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">•</span>
                            </h2>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('superadmin.owners.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.owners.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-building w-5 text-center text-base {{ request()->routeIs('superadmin.owners.*') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Owner Lembaga</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.token-packages.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.token-packages.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-box w-5 text-center text-base {{ request()->routeIs('superadmin.token-packages.*') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Paket Token AI</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400 px-3"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'text-center' : 'text-left'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">PENGATURAN SYSTEM</span>
                                <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">•</span>
                            </h2>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('superadmin.llm.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.llm.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-robot w-5 text-center text-base {{ request()->routeIs('superadmin.llm.*') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Provider AI LLM</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.gateways.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.gateways.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-sliders-h w-5 text-center text-base {{ request()->routeIs('superadmin.gateways.*') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Gateways & Setting</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.logs.index') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('superadmin.logs.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-history w-5 text-center text-base {{ request()->routeIs('superadmin.logs.*') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Log Aktivitas</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    @elseif($isOwner)
                        <!-- ================= OWNER MENU ================= -->
                        <div>
                            <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400 px-3"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'text-center' : 'text-left'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">MENU</span>
                                <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">•</span>
                            </h2>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('tenant.owner.dashboard', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.dashboard') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-chart-pie w-5 text-center text-base {{ request()->routeIs('tenant.owner.dashboard') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Dashboard Owner</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400 px-3"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'text-center' : 'text-left'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">AKADEMIK & KUIS</span>
                                <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">•</span>
                            </h2>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.quizzes.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-magic w-5 text-center text-base {{ request()->routeIs('tenant.owner.quizzes.*') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Manajemen Kuis AI</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.participants.*') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-users-gear w-5 text-center text-base {{ request()->routeIs('tenant.owner.participants.*') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Kelola Peserta Ujian</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.reports', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.reports') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-square-poll-vertical w-5 text-center text-base {{ request()->routeIs('tenant.owner.reports') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Laporan & Hasil Evaluasi</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400 px-3"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'text-center' : 'text-left'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">MODUL & SETTING</span>
                                <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">•</span>
                            </h2>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.tokens') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-coins w-5 text-center text-base {{ request()->routeIs('tenant.owner.tokens') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Saldo Token & Pembelian</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tenant.owner.whatsapp', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.owner.whatsapp') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fab fa-whatsapp w-5 text-center text-base {{ request()->routeIs('tenant.owner.whatsapp') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Notifikasi WhatsApp</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    @elseif($isParticipant)
                        <!-- ================= PARTICIPANT MENU ================= -->
                        <div>
                            <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400 px-3"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'text-center' : 'text-left'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">MENU</span>
                                <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">•</span>
                            </h2>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.participant.dashboard') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-play-circle w-5 text-center text-base {{ request()->routeIs('tenant.participant.dashboard') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Beranda Kuis & Ujian</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-3 text-[11px] uppercase font-bold tracking-wider text-gray-400 px-3"
                                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'text-center' : 'text-left'">
                                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">RIWAYAT & EVALUASI</span>
                                <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">•</span>
                            </h2>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('tenant.participant.history', ['tenant' => $tenantSlug]) }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tenant.participant.history') ? 'bg-brand-50 text-brand-500 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <i class="fas fa-clock-rotate-left w-5 text-center text-base {{ request()->routeIs('tenant.participant.history') ? 'text-brand-500' : 'text-gray-400' }}"></i>
                                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">Riwayat & Nilai Ujian</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </nav>
            </div>

            <!-- Footer Unified Tenant & Version Card -->
            <div class="py-4 border-t border-gray-200"
                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                <div class="p-3 rounded-2xl bg-gray-50/80 border border-gray-200 space-y-2.5 shadow-2xs">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-brand-500 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="flex flex-col min-w-0 flex-1">
                            <span class="text-xs font-bold text-gray-900 truncate leading-tight">{{ tenant('name') ?? 'MariLMS System' }}</span>
                            <span class="text-[10px] text-gray-500 truncate">ID: {{ tenant('id') ?? 'Central' }}</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-200/80 flex items-center justify-between text-[11px] text-gray-500 font-medium">
                        <span>MariLMS Platform</span>
                        <span class="px-2 py-0.5 rounded-full bg-brand-50 text-brand-600 font-extrabold border border-brand-200 text-[10px]">v1.5.1</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ============================================================ -->
        <!-- TAILADMIN EXACT APP HEADER & CONTENT WRAPPER -->
        <!-- ============================================================ -->
        <div class="flex-1 transition-all duration-300 ease-in-out min-w-0 flex flex-col min-h-screen"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            
            <!-- TAILADMIN EXACT APP HEADER -->
            <header class="sticky top-0 flex w-full bg-white border-b border-gray-200 z-40">
                <div class="flex items-center justify-between w-full px-4 py-3 xl:px-6">
                    
                    <!-- Left Section: TailAdmin Hamburger Toggle & Search Bar -->
                    <div class="flex items-center gap-3 lg:gap-4">
                        <!-- Desktop Sidebar Toggle Button (Exact TailAdmin SVG & Dimensions) -->
                        <button class="hidden xl:flex items-center justify-center w-10 h-10 lg:w-11 lg:h-11 text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-100 transition"
                            @click="$store.sidebar.toggleExpanded()" aria-label="Toggle Sidebar">
                            <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z" fill="currentColor"></path>
                            </svg>
                        </button>

                        <!-- Mobile Menu Toggle Button -->
                        <button class="flex xl:hidden items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-100 transition"
                            @click="$store.sidebar.toggleMobileOpen()" aria-label="Toggle Mobile Menu">
                            <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z" fill="currentColor"></path>
                            </svg>
                        </button>

                        <!-- TailAdmin Search Bar (Desktop only) -->
                        <div class="hidden sm:block">
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <input type="text" placeholder="Search or type command..."
                                    class="h-11 w-64 md:w-96 rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10 transition" />
                                <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center gap-0.5 rounded-lg border border-gray-200 bg-gray-50 px-2 py-1 text-xs text-gray-400 font-mono">
                                    <span>⌘</span>
                                    <span>K</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: Token Pill, Notification Bell, & User Profile (Exact TailAdmin Image 2 Specs) -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        @if($isOwner)
                            <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenantSlug]) }}"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold hover:bg-amber-100 transition shadow-2xs">
                                <i class="fas fa-coins text-amber-500"></i>
                                <span>Saldo Token: {{ number_format(auth('owner')->user()->tokenBalance->balance ?? 0) }}</span>
                            </a>
                        @endif

                        <!-- TailAdmin Interactive Notification Bell Dropdown -->
                        <div class="relative" x-data="{ notifOpen: false, hasUnread: true }">
                            <button @click="notifOpen = !notifOpen"
                                class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-gray-700 h-11 w-11 hover:bg-gray-100 focus:outline-none">
                                <!-- Orange Badge Dot -->
                                <span x-show="hasUnread" class="absolute right-0.5 top-0.5 z-10 flex h-2.5 w-2.5">
                                    <span class="absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75 animate-ping"></span>
                                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-orange-500 border border-white"></span>
                                </span>

                                <!-- TailAdmin Bell SVG Icon -->
                                <svg class="fill-current text-gray-500" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z" fill="currentColor" />
                                </svg>
                            </button>

                            <!-- Notification Dropdown Menu -->
                            <div x-show="notifOpen"
                                @click.outside="notifOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-200 py-3 z-50">
                                
                                <div class="px-4 pb-2.5 border-b border-gray-100 flex items-center justify-between">
                                    <h4 class="text-xs font-bold text-gray-900">Notifikasi Platform</h4>
                                    <button @click="hasUnread = false" class="text-[11px] font-semibold text-brand-500 hover:text-brand-600">
                                        Tandai Dibaca
                                    </button>
                                </div>

                                <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
                                    <a href="#" class="flex items-start gap-3 p-3.5 hover:bg-gray-50 transition">
                                        <div class="w-8 h-8 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 font-bold text-xs">
                                            <i class="fas fa-magic"></i>
                                        </div>
                                        <div class="space-y-0.5 min-w-0 flex-1 text-xs">
                                            <p class="font-bold text-gray-800 truncate">Kuis AI Berhasil Dibuat</p>
                                            <p class="text-gray-500 text-[11px]">Soal kuis otomatis telah di-generate oleh AI OpenRouter.</p>
                                            <span class="text-[10px] text-gray-400 block pt-0.5">Baru saja</span>
                                        </div>
                                    </a>

                                    <a href="#" class="flex items-start gap-3 p-3.5 hover:bg-gray-50 transition">
                                        <div class="w-8 h-8 rounded-full bg-success-50 text-success-600 flex items-center justify-center shrink-0 font-bold text-xs">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div class="space-y-0.5 min-w-0 flex-1 text-xs">
                                            <p class="font-bold text-gray-800 truncate">Sistem Tenancy & Token Ready</p>
                                            <p class="text-gray-500 text-[11px]">Saldo token dan guard multi-tenancy aktif.</p>
                                            <span class="text-[10px] text-gray-400 block pt-0.5">10 menit yang lalu</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="px-4 pt-2.5 border-t border-gray-100 text-center">
                                    <span class="text-[11px] font-bold text-gray-500">MariLMS AI Real-time Engine</span>
                                </div>
                            </div>
                        </div>

                        <!-- TailAdmin Exact User Profile Component (Image 2) -->
                        <div x-data="{ profileOpen: false }" class="relative">
                            <button @click="profileOpen = !profileOpen"
                                class="flex items-center text-gray-700 focus:outline-none">
                                <span class="mr-3 overflow-hidden rounded-full h-11 w-11 shrink-0 bg-brand-500 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </span>
                                <span class="hidden sm:block mr-1.5 font-medium text-theme-sm text-gray-800">
                                    {{ $user->name ?? 'User' }}
                                </span>
                                <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': profileOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="profileOpen"
                                @click.outside="profileOpen = false"
                                x-transition
                                class="absolute right-0 mt-[17px] w-[260px] flex flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg z-50">
                                
                                <div class="px-2 py-2 border-b border-gray-100 space-y-0.5">
                                    <p class="font-medium text-gray-800 text-theme-sm truncate">{{ $user->name ?? 'User' }}</p>
                                    <p class="text-theme-xs text-gray-500 truncate">{{ $user->email ?? '-' }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $roleBadgeColor }}">
                                        {{ $roleLabel }}
                                    </span>
                                </div>

                                <div class="py-2 border-b border-gray-100 space-y-1">
                                    <a href="{{ $profileRoute }}"
                                        class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 transition">
                                        <span class="text-gray-500 group-hover:text-brand-500">
                                            <i class="fas fa-user-gear text-sm"></i>
                                        </span>
                                        <span>Edit Profile</span>
                                    </a>
                                </div>

                                @php
                                    $logoutAction = $isParticipant
                                        ? route('tenant.logout', ['tenant' => $tenantSlug])
                                        : route('logout');
                                @endphp

                                <form method="POST" action="{{ $logoutAction }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full gap-3 px-3 py-2 mt-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 transition">
                                        <span class="text-gray-500 group-hover:text-error-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                        </span>
                                        <span class="text-error-600">Sign out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Main Content Container (TailAdmin Max-W 2XL) -->
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6 space-y-6 flex-1 w-full">
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
