@extends('layouts.app-backend')

@section('title', 'Edit Profil Saya')

@section('content')
<!-- Page Header Card Wrapper -->
<div class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Pengaturan Profil Saya</h2>
        <p class="text-xs text-gray-500">Kelola informasi nama akun, nama lembaga, dan kata sandi keamanan Anda.</p>
    </div>
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-brand-50 text-brand-600 text-xs font-bold border border-brand-200">
        <i class="fas fa-user-check text-xs"></i>
        <span>{{ $roleLabel }}</span>
    </span>
</div>

<!-- Mandatory Information Card (Rule 5.E GEMINI.md) -->
<div x-data="{ showInfoCard: true }" class="space-y-4">
    <div x-show="showInfoCard" x-transition 
        class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs relative">
        <button @click="showInfoCard = false" class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 text-sm">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-500 border border-brand-200 flex items-center justify-center font-bold shrink-0">
                <i class="fas fa-user-gear text-lg"></i>
            </div>
            <div class="space-y-1.5 text-xs text-gray-600 leading-relaxed pr-6">
                <h4 class="font-bold text-gray-900 text-sm">
                    Fungsi & Panduan Modul Pengaturan Profil
                </h4>
                <p>
                    Halaman ini digunakan untuk memperbarui identitas pribadi Anda, nama organisasi/lembaga (khusus Owner), serta mengganti kata sandi login secara aman.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-1 font-medium text-gray-700">
                    <div class="flex items-center gap-2"><i class="fas fa-id-card text-brand-500"></i> Update Nama & Identitas</div>
                    <div class="flex items-center gap-2"><i class="fas fa-building text-success-600"></i> Informasi Lembaga</div>
                    <div class="flex items-center gap-2"><i class="fas fa-key text-amber-500"></i> Kredensial Password</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Form 1: Informasti Profil (8 Cols) -->
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
@endsection
