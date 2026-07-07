<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MariLMS SuperAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f1117;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .login-container {
            width: 100%; max-width: 420px;
        }
        .login-brand {
            text-align: center; margin-bottom: 40px;
        }
        .login-brand-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 800; color: white;
            margin-bottom: 16px;
        }
        .login-brand h1 { font-size: 28px; font-weight: 800; color: #e2e8f0; letter-spacing: -1px; }
        .login-brand p { font-size: 14px; color: #64748b; margin-top: 4px; }
        .login-card {
            background: #1e2030;
            border: 1px solid #2e3148;
            border-radius: 16px;
            padding: 36px;
        }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #94a3b8; margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 12px 14px;
            background: #252840; border: 1px solid #2e3148;
            border-radius: 10px; color: #e2e8f0;
            font-size: 14px; font-family: inherit;
            transition: all 0.2s;
        }
        .form-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-error { font-size: 12px; color: #ef4444; margin-top: 4px; }
        .btn-login {
            width: 100%; padding: 12px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            font-family: inherit; transition: all 0.2s;
        }
        .btn-login:hover { background: linear-gradient(135deg, #818cf8, #6366f1); box-shadow: 0 0 20px rgba(99,102,241,0.3); transform: translateY(-1px); }
        .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .remember-row input { accent-color: #6366f1; }
        .remember-row label { font-size: 13px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-brand">
            <div class="login-brand-icon">M</div>
            <h1>MariLMS</h1>
            <p>Super Admin Panel</p>
        </div>
        <div class="login-card">
            <form method="POST" action="{{ route('superadmin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="superadmin@marilms.com">
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>
                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>
    </div>
</body>
</html>
