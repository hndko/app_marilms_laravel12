<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(($mode ?? 'central_login') === 'owner_register')
            Daftar Tenant Baru — MariLMS AI
        @elseif(($mode ?? 'central_login') === 'participant_register')
            Registrasi Peserta — {{ $tenantModel->name ?? $tenant ?? 'MariLMS AI' }}
        @elseif(($mode ?? 'central_login') === 'participant_login')
            Login Peserta — {{ $tenantModel->name ?? $tenant ?? 'MariLMS AI' }}
        @else
            Masuk — MariLMS AI Platform
        @endif
    </title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --bg-input: #ffffff;
            --border: #e2e8f0;
            --text-main: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --danger: #dc2626;
            --success: #16a34a;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .auth-wrapper {
            width: 100%;
            max-width: 440px;
        }
        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            justify-content: center;
        }
        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .brand-text h1 {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }
        .brand-text span {
            font-size: 11px;
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 24px;
        }
        .form-title h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
        }
        .form-title p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
            line-height: 1.5;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-secondary);
        }
        .input-group {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 11px 16px 11px 40px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 14px;
            transition: all 0.15s ease-in-out;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        .btn-submit {
            width: 100%;
            padding: 12px 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }
        .btn-submit:hover {
            background: var(--primary-hover);
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: var(--danger);
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .footer-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .footer-link a:hover {
            text-decoration: underline;
        }
        .tenant-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: var(--primary);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <!-- Brand Header -->
            <div class="brand-logo">
                <div class="brand-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="brand-text">
                    <h1>{{ $tenantModel->name ?? 'MariLMS AI' }}</h1>
                    <span>
                        @if(isset($tenantModel))
                            Portal Peserta Ujian
                        @else
                            Platform LMS Multi-Tenant
                        @endif
                    </span>
                </div>
            </div>

            @if(in_array(($mode ?? 'central_login'), ['owner_register', 'participant_register']))
                <!-- MODE: REGISTER -->
                <div class="form-title">
                    <h2>
                        @if(($mode ?? 'central_login') === 'owner_register')
                            Daftar Lembaga / Tenant Baru
                        @else
                            Registrasi Peserta Ujian
                        @endif
                    </h2>
                    <p>
                        @if(($mode ?? 'central_login') === 'owner_register')
                            Dapatkan 50 Token AI gratis untuk pembuatan kuis otomatis
                        @else
                            Buat akun peserta untuk mengikuti ujian di {{ $tenantModel->name ?? 'lembaga ini' }}
                        @endif
                    </p>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-circle-exclamation" style="font-size: 16px; margin-top: 2px;"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    $registerAction = ($mode ?? 'central_login') === 'participant_register'
                        ? route('tenant.register.submit', ['tenant' => $tenant, 'token' => $token ?? 'default'])
                        : route('register.submit');
                @endphp

                <form method="POST" action="{{ $registerAction }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso">
                        </div>
                    </div>

                    @if(($mode ?? 'central_login') === 'owner_register')
                        <div class="form-group">
                            <label class="form-label">Nama Lembaga / Sekolah / Organisasi</label>
                            <div class="input-group">
                                <i class="fas fa-building input-icon"></i>
                                <input type="text" name="organization_name" class="form-input" value="{{ old('organization_name') }}" required placeholder="Contoh: SMA Negeri 1 Jakarta">
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="user@example.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" class="form-input" required placeholder="Minimal 8 karakter">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <i class="fas fa-key input-icon"></i>
                            <input type="password" name="password_confirmation" class="form-input" required placeholder="Ulangi password">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-user-plus"></i>
                        @if(($mode ?? 'central_login') === 'owner_register')
                            Daftar & KLAIM 50 Token Gratis
                        @else
                            Daftar Peserta Baru
                        @endif
                    </button>
                </form>

                <div class="footer-link">
                    Sudah punya akun? 
                    <a href="{{ isset($tenant) ? route('tenant.login', ['tenant' => $tenant]) : route('login') }}">Masuk Sekarang</a>
                </div>

            @else
                <!-- MODE: LOGIN -->
                <div class="form-title">
                    <h2>
                        @if(($mode ?? 'central_login') === 'participant_login')
                            Login Peserta Ujian
                        @else
                            Masuk Akun Anda
                        @endif
                    </h2>
                    <p>Masukkan email dan password untuk melanjutkan ke portal</p>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-circle-exclamation" style="font-size: 16px; margin-top: 2px;"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    $loginAction = ($mode ?? 'central_login') === 'participant_login'
                        ? route('tenant.login.submit', ['tenant' => $tenant])
                        : route('login.submit');
                @endphp

                <form method="POST" action="{{ $loginAction }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="user@example.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" class="form-input" required placeholder="••••••••">
                        </div>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); cursor: pointer;">
                            <input type="checkbox" name="remember" id="remember" checked style="width: 16px; height: 16px; accent-color: var(--primary);">
                            Ingat sesi saya
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-right-to-bracket"></i> Masuk Sekarang
                    </button>
                </form>

                @if(($mode ?? 'central_login') !== 'participant_login')
                    <div class="footer-link">
                        Belum punya akun lembaga? <a href="{{ route('register') }}">Daftar Sekarang</a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</body>
</html>
