@extends('layouts.app-auth')

@section('content')
<div class="relative z-1 bg-white p-4 sm:p-0 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="relative flex min-h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
        
        <!-- Form Section -->
        <div class="flex w-full flex-1 flex-col justify-between p-6 sm:p-8 lg:w-1/2 xl:p-12">
            <!-- Back to Home -->
            <div class="w-full max-w-md mx-auto">
                <a href="{{ route('landing') }}"
                    class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition-colors dark:text-gray-400 dark:hover:text-blue-400">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            <!-- Main Auth Card -->
            <div class="w-full max-w-md mx-auto my-auto py-6">
                
                <!-- Brand Header -->
                <div class="mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ $tenantModel->name ?? 'MariLMS AI' }}
                        </h1>
                        <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">
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
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            @if(($mode ?? 'central_login') === 'owner_register')
                                Daftar Lembaga Baru
                            @else
                                Registrasi Peserta Ujian
                            @endif
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            @if(($mode ?? 'central_login') === 'owner_register')
                                Dapatkan 50 Token AI gratis untuk pembuatan kuis otomatis
                            @else
                                Buat akun peserta ujian di {{ $tenantModel->name ?? 'lembaga ini' }}
                            @endif
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800 text-red-700 dark:text-red-300 text-xs space-y-1">
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

                    <form method="POST" action="{{ $registerAction }}" class="space-y-4">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-user text-sm"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                            </div>
                        </div>

                        @if(($mode ?? 'central_login') === 'owner_register')
                            <!-- Nama Lembaga -->
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    Nama Lembaga / Sekolah / Organisasi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                        <i class="fas fa-building text-sm"></i>
                                    </span>
                                    <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
                                        placeholder="Contoh: SMA Negeri 1 Jakarta"
                                        class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                                </div>
                            </div>
                        @endif

                        <!-- Email -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    placeholder="user@example.com"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required
                                    placeholder="Minimal 8 karakter"
                                    class="w-full h-11 pl-10 pr-10 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-key text-sm"></i>
                                </span>
                                <input type="password" name="password_confirmation" required
                                    placeholder="Ulangi kata sandi"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 mt-2">
                            <i class="fas fa-user-plus"></i>
                            <span>
                                @if(($mode ?? 'central_login') === 'owner_register')
                                    Daftar & Klaim 50 Token Gratis
                                @else
                                    Daftar Peserta Baru
                                @endif
                            </span>
                        </button>
                    </form>

                    <div class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
                        Sudah memiliki akun?
                        <a href="{{ isset($tenant) ? route('tenant.login', ['tenant' => $tenant]) : route('login') }}"
                            class="font-semibold text-blue-600 hover:underline dark:text-blue-400">
                            Masuk Sekarang
                        </a>
                    </div>

                @else
                    <!-- ============================================================ -->
                    <!-- MODE: LOGIN -->
                    <!-- ============================================================ -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            @if(($mode ?? 'central_login') === 'participant_login')
                                Login Peserta Ujian
                            @else
                                Masuk ke Akun Anda
                            @endif
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Masukkan email dan kata sandi untuk melanjutkan ke portal
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800 text-red-700 dark:text-red-300 text-xs space-y-1">
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

                    <form method="POST" action="{{ $loginAction }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="user@example.com"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required
                                    placeholder="••••••••"
                                    class="w-full h-11 pl-10 pr-10 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Checkbox Remember -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 cursor-pointer">
                                <input type="checkbox" name="remember" id="remember" checked
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                <span>Ingat sesi saya</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 mt-2">
                            <i class="fas fa-right-to-bracket"></i>
                            <span>Masuk Sekarang</span>
                        </button>
                    </form>

                    @if(($mode ?? 'central_login') !== 'participant_login')
                        <div class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
                            Belum memiliki akun lembaga?
                            <a href="{{ route('register') }}"
                                class="font-semibold text-blue-600 hover:underline dark:text-blue-400">
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

        <!-- Right Banner Side (TailAdmin Brand Panel) -->
        <div class="relative hidden w-full items-center justify-center bg-slate-900 lg:flex lg:w-1/2 p-12 overflow-hidden">
            <!-- Decorative Grid Background -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
            
            <div class="relative z-10 max-w-md text-center text-white space-y-6">
                <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-bold text-3xl mx-auto shadow-lg shadow-blue-500/30">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="text-2xl font-bold font-display tracking-tight text-white">
                    MariLMS AI Portal
                </h3>
                <p class="text-sm text-gray-300 leading-relaxed">
                    Platform SaaS evaluasi dan ujian digital berbantuan kecerdasan buatan dengan proteksi anti-cheat real-time & isolasi data multi-tenant.
                </p>
                <div class="pt-4 flex justify-center gap-6 text-xs text-gray-400">
                    <span class="flex items-center gap-1.5"><i class="fas fa-bolt text-amber-400"></i> AI Quiz Generator</span>
                    <span class="flex items-center gap-1.5"><i class="fas fa-shield-check text-emerald-400"></i> Anti-Cheat Timer</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
