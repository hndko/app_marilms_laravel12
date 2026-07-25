@extends('layouts.app-auth')

@section('content')
<div class="relative z-1 bg-white p-4 sm:p-0 min-h-screen flex items-center justify-center">
    <div class="relative flex min-h-screen w-full flex-col justify-center sm:p-0 lg:flex-row bg-white">
        
        <!-- Left Form Section (TailAdmin Form Styling) -->
        <div class="flex w-full flex-1 flex-col justify-between p-6 sm:p-8 lg:w-1/2 xl:p-12 bg-white">
            <!-- Back to Home -->
            <div class="w-full max-w-md mx-auto">
                <a href="{{ route('landing') }}"
                    class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-500 transition-colors">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            <!-- Main Auth Card -->
            <div class="w-full max-w-md mx-auto my-auto py-6">
                
                <!-- Brand Header -->
                <div class="mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white font-bold text-lg shadow-theme-xs">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight">
                            {{ $tenantModel->name ?? 'MariLMS AI' }}
                        </h1>
                        <span class="text-xs font-semibold uppercase tracking-wider text-brand-500">
                            @if(isset($tenantModel))
                                Portal Peserta Ujian
                            @else
                                Multi-Tenant Exam Platform
                            @endif
                        </span>
                    </div>
                </div>

                @if(in_array(($mode ?? 'central_login'), ['owner_register', 'participant_register']))
                    <!-- ============================================================ -->
                    <!-- MODE: REGISTER -->
                    <!-- ============================================================ -->
                    <div class="mb-6">
                        <h2 class="text-title-sm font-semibold text-gray-800">
                            @if(($mode ?? 'central_login') === 'owner_register')
                                Daftar Lembaga Baru
                            @else
                                Registrasi Peserta Ujian
                            @endif
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            @if(($mode ?? 'central_login') === 'owner_register')
                                Dapatkan 50 Token AI gratis untuk pembuatan kuis otomatis
                            @else
                                Buat akun peserta ujian di {{ $tenantModel->name ?? 'lembaga ini' }}
                            @endif
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-error-50 border border-error-200 text-error-600 text-xs space-y-1">
                            <div class="font-bold flex items-center gap-2">
                                <i class="fas fa-circle-exclamation text-sm"></i>
                                <span>Terdapat kesalahan pada pendaftaran:</span>
                            </div>
                            <ul class="list-disc list-inside pl-1 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $registerAction = ($mode ?? 'central_login') === 'participant_register'
                            ? route('tenant.register.submit', ['tenant' => $tenant, 'token' => $token ?? 'default'])
                            : route('register.submit');
                    @endphp

                    <form method="POST" action="{{ $registerAction }}" x-data="{ isSubmitting: false }" @submit="isSubmitting = true" class="space-y-4">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Nama Lengkap <span class="text-error-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-user text-sm"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                    placeholder="Contoh: Budi Santoso"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden transition" />
                            </div>
                        </div>

                        @if(($mode ?? 'central_login') === 'owner_register')
                            <!-- Nama Lembaga -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Nama Lembaga / Sekolah / Organisasi <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                        <i class="fas fa-building text-sm"></i>
                                    </span>
                                    <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
                                        placeholder="Contoh: SMA Negeri 1 Jakarta"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden transition" />
                                </div>
                            </div>
                        @endif

                        <!-- Email -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Alamat Email <span class="text-error-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    placeholder="user@example.com"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden transition" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Kata Sandi <span class="text-error-500">*</span>
                            </label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required
                                    placeholder="Minimal 8 karakter"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden transition" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Konfirmasi Kata Sandi <span class="text-error-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-key text-sm"></i>
                                </span>
                                <input type="password" name="password_confirmation" required
                                    placeholder="Ulangi kata sandi"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden transition" />
                            </div>
                        </div>

                        <!-- Submit Button with Loading Effect -->
                        <button type="submit"
                            :disabled="isSubmitting"
                            :class="isSubmitting ? 'opacity-75 cursor-wait' : ''"
                            class="bg-brand-500 hover:bg-brand-600 shadow-theme-xs flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition mt-2 cursor-pointer disabled:cursor-not-allowed">
                            <template x-if="!isSubmitting">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-user-plus"></i>
                                    <span>
                                        @if(($mode ?? 'central_login') === 'owner_register')
                                            Daftar & Klaim 50 Token Gratis
                                        @else
                                            Daftar Peserta Baru
                                        @endif
                                    </span>
                                </span>
                            </template>
                            <template x-if="isSubmitting">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span>Memproses Pendaftaran...</span>
                                </span>
                            </template>
                        </button>
                    </form>

                    <div class="mt-6 text-center text-xs text-gray-500">
                        Sudah memiliki akun?
                        <a href="{{ isset($tenant) ? route('tenant.login', ['tenant' => $tenant]) : route('login') }}"
                            class="font-semibold text-brand-500 hover:text-brand-600">
                            Masuk Sekarang
                        </a>
                    </div>

                @else
                    <!-- ============================================================ -->
                    <!-- MODE: LOGIN -->
                    <!-- ============================================================ -->
                    <div class="mb-6">
                        <h2 class="text-title-sm font-semibold text-gray-800">
                            @if(($mode ?? 'central_login') === 'participant_login')
                                Login Peserta Ujian
                            @else
                                Masuk ke Akun Anda
                            @endif
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Masukkan email dan kata sandi untuk melanjutkan ke portal
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-error-50 border border-error-200 text-error-600 text-xs space-y-1">
                            <div class="font-bold flex items-center gap-2">
                                <i class="fas fa-circle-exclamation text-sm"></i>
                                <span>Gagal masuk ke akun:</span>
                            </div>
                            <ul class="list-disc list-inside pl-1 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $loginAction = ($mode ?? 'central_login') === 'participant_login'
                            ? route('tenant.login.submit', ['tenant' => $tenant])
                            : route('login.submit');
                    @endphp

                    <form method="POST" action="{{ $loginAction }}" x-data="{ isSubmitting: false }" @submit="isSubmitting = true" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Alamat Email <span class="text-error-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="user@example.com"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden transition" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700">
                                Kata Sandi <span class="text-error-500">*</span>
                            </label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required
                                    placeholder="••••••••"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-10 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden transition" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Checkbox Remember -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                <input type="checkbox" name="remember" id="remember" checked
                                    class="w-4 h-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                                <span>Ingat sesi saya</span>
                            </label>
                        </div>

                        <!-- Submit Button with Loading Effect -->
                        <button type="submit"
                            :disabled="isSubmitting"
                            :class="isSubmitting ? 'opacity-75 cursor-wait' : ''"
                            class="bg-brand-500 hover:bg-brand-600 shadow-theme-xs flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium text-white transition mt-2 cursor-pointer disabled:cursor-not-allowed">
                            <template x-if="!isSubmitting">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-right-to-bracket"></i>
                                    <span>Masuk Sekarang</span>
                                </span>
                            </template>
                            <template x-if="isSubmitting">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span>Memproses Sesi...</span>
                                </span>
                            </template>
                        </button>
                    </form>

                    @if(($mode ?? 'central_login') !== 'participant_login')
                        <div class="mt-6 text-center text-xs text-gray-500">
                            Belum memiliki akun lembaga?
                            <a href="{{ route('register') }}"
                                class="font-semibold text-brand-500 hover:text-brand-600">
                                Daftar Sekarang
                            </a>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Footer Text -->
            <div class="w-full max-w-md mx-auto text-center text-xs text-gray-400 py-4">
                &copy; {{ date('Y') }} MariLMS AI — Multi-Tenant Learning Management System
            </div>
        </div>

        <!-- Right Side Panel (TailAdmin bg-brand-950 Theme Panel) -->
        <div class="bg-brand-950 relative hidden h-full w-full items-center justify-center lg:flex lg:w-1/2 p-12 overflow-hidden">
            <!-- TailAdmin Grid Shape Background -->
            <div class="absolute right-0 top-0 -z-1 w-full max-w-[250px] xl:max-w-[450px] opacity-40">
                <img src="{{ asset('images/shape/grid-01.svg') }}" alt="grid shape" />
            </div>
            <div class="absolute bottom-0 left-0 -z-1 w-full max-w-[250px] rotate-180 xl:max-w-[450px] opacity-40">
                <img src="{{ asset('images/shape/grid-01.svg') }}" alt="grid shape" />
            </div>
            
            <div class="relative z-10 max-w-sm text-center text-white space-y-6">
                <div class="w-16 h-16 rounded-2xl bg-brand-500 flex items-center justify-center text-white font-bold text-3xl mx-auto shadow-lg shadow-brand-500/30">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="text-2xl font-bold font-display tracking-tight text-white">
                    MariLMS AI Portal
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed">
                    Platform SaaS evaluasi dan ujian digital berbantuan kecerdasan buatan dengan proteksi anti-cheat real-time & isolasi data multi-tenant.
                </p>
                <div class="pt-4 flex flex-wrap justify-center gap-3 text-xs text-gray-200">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 backdrop-blur-xs">
                        <i class="fas fa-bolt text-amber-400"></i> AI Quiz Generator
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 backdrop-blur-xs">
                        <i class="fas fa-shield-check text-emerald-400"></i> Anti-Cheat Timer
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
