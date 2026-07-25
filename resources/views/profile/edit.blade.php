@extends('layouts.app-backend')

@section('title', 'Edit Profil Saya')

@section('content')
<div x-data="{ 
    showInfoModal: false,
    init() {
        this.$watch('showInfoModal', value => {
            document.body.style.overflow = value ? 'hidden' : 'unset';
        });
    }
}" class="space-y-6">

    <!-- Page Header Card Wrapper with Panduan Modul Button -->
    <div class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Pengaturan Profil Saya</h2>
            <p class="text-xs text-gray-500">Kelola informasi nama akun, nama lembaga, dan kata sandi keamanan Anda.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button @click="showInfoModal = true" 
                class="px-3.5 py-2.5 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 text-xs font-bold transition flex items-center gap-2 shadow-2xs">
                <i class="fas fa-circle-info text-brand-500 text-sm"></i>
                <span>Panduan Modul</span>
            </button>

            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gray-50 text-gray-700 text-xs font-bold border border-gray-200">
                <i class="fas fa-user-check text-xs text-brand-500"></i>
                <span>{{ $roleLabel }}</span>
            </span>
        </div>
    </div>

    <!-- TailAdmin Perfect Modal Component (Exact Header, Scroll Body & Fixed Footer) -->
    <div x-show="showInfoModal" x-cloak
        @keydown.escape.window="showInfoModal = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-gray-900/50 backdrop-blur-xs overflow-y-auto"
        style="display: none;">
        
        <!-- Backdrop Click to Close -->
        <div @click="showInfoModal = false" class="fixed inset-0 h-full w-full"></div>

        <!-- Modal Dialog Box -->
        <div class="relative w-full max-w-[580px] rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-gray-200 z-10 flex flex-col max-h-[85vh] animate-in fade-in zoom-in duration-150">
            
            <!-- Modal Header (Fixed Top) -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-500 border border-brand-200 flex items-center justify-center font-bold shrink-0">
                        <i class="fas fa-user-gear text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Panduan Modul Profil</h3>
                        <p class="text-xs text-gray-500">Informasi perbaikan data akun dan keamanan.</p>
                    </div>
                </div>
                <button @click="showInfoModal = false" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Modal Body (Dedicated Scrollable Container with Bottom Padding) -->
            <div class="flex-1 overflow-y-auto py-4 space-y-4 text-xs text-gray-600 leading-relaxed pr-2">
                <div class="space-y-1.5 bg-gray-50 p-4 rounded-2xl border border-gray-200/80">
                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-id-card text-brand-500"></i>
                        Fungsi & Perbaruan Identitas
                    </h4>
                    <p>
                        Gunakan formulir sebelah kiri untuk memperbarui Nama Lengkap dan Nama Lembaga. Formulir sebelah kanan digunakan khusus untuk mengganti kata sandi secara berkala.
                    </p>
                </div>
            </div>

            <!-- Modal Footer (Fixed Bottom outside Body Scroll Container) -->
            <div class="pt-4 border-t border-gray-100 flex justify-end shrink-0">
                <button @click="showInfoModal = false" 
                    class="px-6 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Form 1: Informasi Profil (7 Cols) -->
        <div class="lg:col-span-7 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-base font-bold text-gray-900">Informasi Pribadi</h3>
                <p class="text-xs text-gray-500">Perbarui identitas akun pengguna Anda.</p>
            </div>

            @php
                $actionRoute = $isSuperAdmin 
                    ? route('superadmin.profile.update') 
                    : ($isOwner 
                        ? route('tenant.owner.profile.update', ['tenant' => $tenantSlug]) 
                        : route('tenant.participant.profile.update', ['tenant' => $tenantSlug]));
            @endphp

            <form method="POST" action="{{ $actionRoute }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama Lengkap -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-xs font-bold text-gray-700">
                        Nama Lengkap <span class="text-error-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-user text-xs"></i>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            placeholder="Masukkan nama lengkap Anda..."
                            class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs @error('name') border-error-500 @enderror" />
                    </div>
                    @error('name')
                        <p class="text-[11px] font-semibold text-error-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address (Read-Only) -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-gray-700">
                        Alamat Email (Akun Login)
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input type="email" id="email" value="{{ $user->email }}" disabled
                            class="h-11 w-full rounded-xl border border-gray-200 bg-gray-100 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-500 cursor-not-allowed shadow-2xs" />
                    </div>
                    <p class="text-[10px] text-gray-400">Alamat email dikunci untuk keamanan akun.</p>
                </div>

                @if($isOwner)
                    <!-- Nama Lembaga / Organisasi -->
                    <div class="space-y-1.5">
                        <label for="organization_name" class="block text-xs font-bold text-gray-700">
                            Nama Lembaga / Sekolah / Instansi
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-building text-xs"></i>
                            </span>
                            <input type="text" name="organization_name" id="organization_name" value="{{ old('organization_name', $user->organization_name ?? '') }}"
                                placeholder="Contoh: SMA Negeri 1 / MariLMS Academy..."
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="space-y-1.5">
                        <label for="phone_number" class="block text-xs font-bold text-gray-700">
                            Nomor WhatsApp Penanggung Jawab
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fab fa-whatsapp text-xs text-success-600"></i>
                            </span>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}"
                                placeholder="Contoh: 081234567890..."
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                        </div>
                    </div>
                @endif

                <div class="pt-3 border-t border-gray-100 flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Form 2: Ganti Password (5 Cols) -->
        <div class="lg:col-span-5 rounded-2xl border border-gray-200 bg-white p-5 md:p-6 shadow-theme-xs space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-base font-bold text-gray-900">Keamanan & Password</h3>
                <p class="text-xs text-gray-500">Ubah kata sandi akun pengguna Anda.</p>
            </div>

            <form method="POST" action="{{ $actionRoute }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Keep Name so validation passes -->
                <input type="hidden" name="name" value="{{ $user->name }}" />
                @if($isOwner)
                    <input type="hidden" name="organization_name" value="{{ $user->organization_name ?? '' }}" />
                @endif

                <!-- Password Saat Ini -->
                <div class="space-y-1.5">
                    <label for="current_password" class="block text-xs font-bold text-gray-700">
                        Kata Sandi Saat Ini <span class="text-error-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input type="password" name="current_password" id="current_password" required
                            placeholder="Masukkan password saat ini..."
                            class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs @error('current_password') border-error-500 @enderror" />
                    </div>
                    @error('current_password')
                        <p class="text-[11px] font-semibold text-error-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold text-gray-700">
                        Kata Sandi Baru <span class="text-error-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-key text-xs"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                            placeholder="Minimal 8 karakter..."
                            class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs @error('password') border-error-500 @enderror" />
                    </div>
                    @error('password')
                        <p class="text-[11px] font-semibold text-error-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700">
                        Konfirmasi Kata Sandi Baru <span class="text-error-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-check-double text-xs"></i>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            placeholder="Ulangi password baru..."
                            class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-2">
                        <i class="fas fa-shield-halved"></i>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
