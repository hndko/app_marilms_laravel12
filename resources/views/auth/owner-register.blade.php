<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun Owner — MariLMS</title>
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
            padding: 30px 20px;
            background-image: 
                radial-gradient(at 10% 20%, rgba(99,102,241,0.15) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(6,182,212,0.15) 0px, transparent 50%);
        }
        .register-card {
            width: 100%;
            max-width: 500px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);
            position: relative;
            overflow: hidden;
        }
        .register-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            justify-content: center;
        }
        .brand-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 800; color: white;
            box-shadow: 0 8px 16px rgba(99,102,241,0.3);
        }
        .brand-text h1 { font-size: 22px; font-weight: 800; color: white; letter-spacing: -0.5px; }
        .brand-text span { font-size: 12px; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; }
        
        .form-title { text-align: center; margin-bottom: 24px; }
        .form-title h2 { font-size: 18px; font-weight: 700; color: white; }
        .form-title p { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #cbd5e1; }
        .form-input {
            width: 100%; padding: 12px 16px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: white; font-size: 14px;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .btn-submit {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .btn-submit:hover {
            opacity: 0.95;
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
        }
        .footer-link {
            text-align: center; margin-top: 24px; font-size: 13px; color: var(--text-muted);
        }
        .footer-link a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .footer-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="brand-logo">
            <div class="brand-icon">M</div>
            <div class="brand-text">
                <h1>MariLMS</h1>
                <span>Registrasi Tenant</span>
            </div>
        </div>

        <div class="form-title">
            <h2>Buat Akun Tenant Baru</h2>
            <p>Dapatkan 50 Token Gratis langsung saat pendaftaran!</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <ul style="margin-left: 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('owner.register.submit') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap Owner</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso">
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="budi@sekolah.sch.id">
            </div>

            <div class="form-group">
                <label class="form-label">Nama Organisasi / Sekolah / Kampus</label>
                <input type="text" name="organization_name" class="form-input" value="{{ old('organization_name') }}" required placeholder="Contoh: SMA Negeri 1 Jakarta / Bina Sarana">
                <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Nama ini akan digunakan sebagai URL tenant Anda (cth: /sma-negeri-1)</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="Minimal 8 karakter">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required placeholder="Ulangi password">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-user-plus"></i> Daftar & Mulai Sekarang
            </button>
        </form>

        <div class="footer-link">
            Sudah memiliki akun tenant? <a href="{{ route('owner.login') }}">Masuk di Sini</a>
        </div>
    </div>
</body>
</html>
