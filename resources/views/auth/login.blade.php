<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — MariLMS Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --accent: #06b6d4;
            --bg-body: #0f1117;
            --bg-card: #1e2030;
            --bg-input: #252840;
            --border: #2e3148;
            --text-primary: #e2e8f0;
            --text-muted: #64748b;
            --danger: #ef4444;
            --success: #10b981;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: 
                radial-gradient(at 10% 20%, rgba(99,102,241,0.18) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(6,182,212,0.18) 0px, transparent 50%);
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 30px -5px rgba(0,0,0,0.6);
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--primary));
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            justify-content: center;
        }
        .brand-icon {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 800; color: white;
            box-shadow: 0 8px 20px rgba(99,102,241,0.35);
        }
        .brand-text h1 { font-size: 24px; font-weight: 800; color: white; letter-spacing: -0.5px; }
        .brand-text span { font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; }
        
        .form-title { text-align: center; margin-bottom: 24px; }
        .form-title h2 { font-size: 19px; font-weight: 700; color: white; }
        .form-title p { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #cbd5e1; }
        .input-group { position: relative; }
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
            padding: 12px 16px 12px 40px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: white; font-size: 14px;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(6,182,212,0.15);
        }
        .btn-submit {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            transform: translateY(-1px);
        }
        .alert-error {
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.3);
            color: var(--danger);
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .footer-link {
            text-align: center; margin-top: 24px; font-size: 13px; color: var(--text-muted);
        }
        .footer-link a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .footer-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-logo">
            <div class="brand-icon">M</div>
            <div class="brand-text">
                <h1>MariLMS</h1>
                <span>Platform Portal</span>
            </div>
        </div>

        <div class="form-title">
            <h2>Masuk ke Akun Anda</h2>
            <p>Masukkan email dan password untuk melanjutkan</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle" style="font-size: 16px;"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="nama@organisasi.com">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="remember" id="remember" checked style="width: 16px; height: 16px; accent-color: var(--primary);">
                <label for="remember" style="font-size: 13px; color: var(--text-muted); cursor: pointer;">Ingat sesi saya</label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-right-to-bracket"></i> Masuk Sekarang
            </button>
        </form>

        <div class="footer-link">
            Belum punya akun tenant? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
    </div>
</body>
</html>
