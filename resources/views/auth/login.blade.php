@extends('layouts.app-auth')

@section('content')
<div class="relative z-1 bg-slate-50 p-4 sm:p-0 min-h-screen flex items-center justify-center">
    <div class="relative flex min-h-screen w-full flex-col justify-center sm:p-0 lg:flex-row bg-slate-50">
        
        <!-- Left Form Section (Clean Light Mode) -->
        <div class="flex w-full flex-1 flex-col justify-between p-6 sm:p-8 lg:w-1/2 xl:p-12 bg-white">
            <!-- Back to Home -->
            <div class="w-full max-w-md mx-auto">
                <a href="{{ route('landing') }}"
                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
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
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                            {{ $tenantModel->name ?? 'MariLMS AI' }}
                        </h1>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600">
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
                        <h2 class="text-2xl font-bold text-slate-900">
                            @if(($mode ?? 'central_login') === 'owner_register')
                                Daftar Lembaga Baru
                            @else
                                Registrasi Peserta Ujian
                            @endif
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            @if(($mode ?? 'central_login') === 'owner_register')
                                Dapatkan 50 Token AI gratis untuk pembuatan kuis otomatis
                            @else
                                Buat akun peserta ujian di {{ $tenantModel->name ?? 'lembaga ini' }}
                            @endif
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
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
                            <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fas fa-user text-sm"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                            </div>
                        </div>

                        @if(($mode ?? 'central_login') === 'owner_register')
                            <!-- Nama Lembaga -->
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                    Nama Lembaga / Sekolah / Organisasi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <i class="fas fa-building text-sm"></i>
                                    </span>
                                    <input type="text" name="organization_name" value="{{ old('organization_name') }}" required
                                        placeholder="Contoh: SMA Negeri 1 Jakarta"
                                        class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                                </div>
                            </div>
                        @endif

                        <!-- Email -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    placeholder="user@example.com"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required
                                    placeholder="Minimal 8 karakter"
                                    class="w-full h-11 pl-10 pr-10 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fas fa-key text-sm"></i>
                                </span>
                                <input type="password" name="password_confirmation" required
                                    placeholder="Ulangi kata sandi"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
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

                    <div class="mt-6 text-center text-xs text-slate-500">
                        Sudah memiliki akun?
                        <a href="{{ isset($tenant) ? route('tenant.login', ['tenant' => $tenant]) : route('login') }}"
                            class="font-semibold text-blue-600 hover:underline">
                            Masuk Sekarang
                        </a>
                    </div>

                @else
                    <!-- ============================================================ -->
                    <!-- MODE: LOGIN -->
                    <!-- ============================================================ -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-slate-900">
                            @if(($mode ?? 'central_login') === 'participant_login')
                                Login Peserta Ujian
                            @else
                                Masuk ke Akun Anda
                            @endif
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Masukkan email dan kata sandi untuk melanjutkan ke portal
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
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
                            <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="user@example.com"
                                    class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ showPassword: false }" class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required
                                    placeholder="••••••••"
                                    class="w-full h-11 pl-10 pr-10 rounded-xl border border-slate-300 bg-white text-sm text-slate-900 placeholder-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none" />
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Checkbox Remember -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                                <input type="checkbox" name="remember" id="remember" checked
                                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
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
                        <div class="mt-6 text-center text-xs text-slate-500">
                            Belum memiliki akun lembaga?
                            <a href="{{ route('register') }}"
                                class="font-semibold text-blue-600 hover:underline">
                                Daftar Sekarang
                            </a>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Footer Text -->
            <div class="w-full max-w-md mx-auto text-center text-xs text-slate-400 py-4">
                &copy; {{ date('Y') }} MariLMS AI — Multi-Tenant Learning Management System
            </div>
        </div>

        <!-- Right Side Panel (Pure Light Mode Enterprise Banner) -->
        <div class="relative hidden w-full items-center justify-center bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-50/70 border-l border-slate-200 lg:flex lg:w-1/2 p-12 overflow-hidden">
            <!-- Subtle Grid Background -->
            <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#2563eb_1px,transparent_1px)] [background-size:20px_20px]"></div>
            
            <div class="relative z-10 max-w-md text-center space-y-6">
                <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-bold text-3xl mx-auto shadow-md shadow-blue-500/20">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="text-2xl font-bold font-display tracking-tight text-slate-900">
                    MariLMS AI Portal
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Platform SaaS evaluasi dan ujian digital berbantuan kecerdasan buatan dengan proteksi anti-cheat real-time & isolasi data multi-tenant.
                </p>
                <div class="pt-4 flex justify-center gap-4 text-xs font-semibold text-slate-700">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 shadow-xs"><i class="fas fa-bolt text-amber-500"></i> AI Quiz Generator</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 shadow-xs"><i class="fas fa-shield-check text-emerald-600"></i> Anti-Cheat Timer</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
