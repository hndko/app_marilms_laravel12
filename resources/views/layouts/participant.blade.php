<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Peserta') — MariLMS AI</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Design System CSS -->
    <link rel="stylesheet" href="{{ asset('css/owner.css') }}">
    @yield('styles')
</head>
<body style="background: var(--bg-dark); color: var(--text-white); font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; display: flex; flex-direction: column;">

    <!-- Top Navigation Bar -->
    <header style="background: rgba(22,25,35,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 1000; padding: 0 32px; height: 72px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 32px;">
            <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenant ?? request()->segment(1)]) }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <img src="{{ asset('images/logo.png') }}" alt="MariLMS Logo" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 15px rgba(6,182,212,0.4);">
                <div>
                    <span style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: white; letter-spacing: -0.5px; display: block;">
                        {{ strtoupper($tenant ?? request()->segment(1)) }} <span style="color: var(--accent); font-weight: 500;">EXAM</span>
                    </span>
                    <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Portal Peserta Ujian</span>
                </div>
            </a>

            <nav style="display: flex; gap: 8px;">
                <a href="{{ route('tenant.participant.dashboard', ['tenant' => $tenant ?? request()->segment(1)]) }}" class="btn {{ request()->routeIs('tenant.participant.dashboard') ? 'btn-primary' : 'btn-ghost' }}" style="padding: 8px 16px; font-size: 13px;">
                    <i class="fas fa-home"></i> Beranda
                </a>
                <a href="{{ route('tenant.participant.history', ['tenant' => $tenant ?? request()->segment(1)]) }}" class="btn {{ request()->routeIs('tenant.participant.history') ? 'btn-primary' : 'btn-ghost' }}" style="padding: 8px 16px; font-size: 13px;">
                    <i class="fas fa-history"></i> Riwayat Kuis
                </a>
            </nav>
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
            @auth('participant')
                <div style="display: flex; align-items: center; gap: 12px; background: var(--bg-input); padding: 6px 14px; border-radius: 30px; border: 1px solid var(--border);">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800;">
                        {{ strtoupper(substr(auth('participant')->user()->name, 0, 1)) }}
                    </div>
                    <span style="font-size: 13px; font-weight: 700; color: white;">{{ auth('participant')->user()->name }}</span>
                </div>

                <form method="POST" action="{{ route('tenant.logout', ['tenant' => $tenant ?? request()->segment(1)]) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-icon btn-secondary" title="Keluar dari Portal" style="color: var(--danger);">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            @endauth
        </div>
    </header>

    <!-- Flash Messages -->
    <div style="max-width: 1200px; width: 100%; margin: 20px auto 0; padding: 0 24px;">
        @if(session('success'))
            <div class="alert alert-success" style="padding: 16px; border-radius: 12px; margin-bottom: 20px; background: rgba(16,185,129,0.15); border: 1px solid var(--success); color: white; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-check-circle" style="color: var(--success); font-size: 20px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="padding: 16px; border-radius: 12px; margin-bottom: 20px; background: rgba(239,68,68,0.15); border: 1px solid var(--danger); color: white; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-exclamation-circle" style="color: var(--danger); font-size: 20px;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main style="flex: 1; max-width: 1200px; width: 100%; margin: 0 auto; padding: 24px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer style="background: var(--bg-card); border-top: 1px solid var(--border); padding: 24px; text-align: center; font-size: 12px; color: var(--text-muted); margin-top: auto;">
        &copy; {{ date('Y') }} <strong>MariLMS AI</strong> v{{ config('app.version', '1.4.0') }}. Portal Evaluasi & Ujian Berbasis Artificial Intelligence.
    </footer>

    @yield('scripts')
</body>
</html>
