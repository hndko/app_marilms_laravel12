<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — MariLMS Owner Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --accent: #06b6d4;
            --accent-light: #22d3ee;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;

            --bg-body: #0f1117;
            --bg-sidebar: #161825;
            --bg-card: #1e2030;
            --bg-card-hover: #252840;
            --bg-input: #252840;
            --bg-modal: #1e2030;

            --border: #2e3148;
            --border-light: #3d4167;

            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --text-white: #ffffff;

            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.3), 0 2px 4px -2px rgba(0,0,0,0.2);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.4), 0 4px 6px -4px rgba(0,0,0,0.3);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);

            --sidebar-width: 270px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: var(--transition);
        }

        .sidebar-brand {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 800; color: white;
            flex-shrink: 0;
        }
        .sidebar-brand-text h1 {
            font-size: 16px; font-weight: 700; color: var(--text-white);
            letter-spacing: -0.5px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;
        }
        .sidebar-brand-text span {
            font-size: 11px; color: var(--accent-light);
            text-transform: uppercase; letter-spacing: 1px; font-weight: 600;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .nav-section { margin-bottom: 24px; }
        .nav-section-title {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.5px;
            color: var(--text-muted);
            padding: 0 12px; margin-bottom: 8px;
        }

        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: var(--transition);
            position: relative;
        }
        .nav-link:hover {
            background: var(--bg-card);
            color: var(--text-primary);
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgba(6,182,212,0.15), rgba(99,102,241,0.1));
            color: var(--accent-light);
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px;
            background: var(--accent);
            border-radius: 0 4px 4px 0;
        }
        .nav-link i { width: 20px; text-align: center; font-size: 15px; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 12px;
            padding: 8px;
        }
        .sidebar-user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: white;
            flex-shrink: 0;
        }
        .sidebar-user-info h4 { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .sidebar-user-info span { font-size: 11px; color: var(--text-muted); }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-bar {
            position: sticky; top: 0;
            background: rgba(15, 17, 23, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            z-index: 40;
        }
        .top-bar-left { display: flex; align-items: center; gap: 16px; }
        .top-bar-left h2 {
            font-size: 18px; font-weight: 700;
            background: linear-gradient(135deg, var(--text-white), var(--text-secondary));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .breadcrumb {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: var(--text-muted);
        }
        .breadcrumb a { color: var(--text-secondary); text-decoration: none; }
        .breadcrumb a:hover { color: var(--accent-light); }

        .top-bar-right { display: flex; align-items: center; gap: 16px; }

        .page-content { padding: 32px; }

        /* ── Cards ── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            transition: var(--transition);
        }
        .card:hover { border-color: var(--border-light); }
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header h3 { font-size: 16px; font-weight: 700; color: var(--text-white); }
        .card-body { padding: 24px; }
        .card-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: flex-end; gap: 12px;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 600;
            text-decoration: none;
            border: none; cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            box-shadow: 0 0 20px rgba(99,102,241,0.3);
            transform: translateY(-1px);
        }
        .btn-accent {
            background: linear-gradient(135deg, var(--accent), #0891b2);
            color: white;
        }
        .btn-accent:hover {
            background: linear-gradient(135deg, var(--accent-light), var(--accent));
            box-shadow: 0 0 20px rgba(6,182,212,0.3);
            transform: translateY(-1px);
        }
        .btn-success { background: var(--success); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-warning { background: var(--warning); color: #1e1e1e; }
        .btn-ghost {
            background: transparent; color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover {
            background: var(--bg-card); border-color: var(--border-light);
            color: var(--text-primary);
        }
        .btn-sm { padding: 6px 14px; font-size: 13px; }
        .btn-xs { padding: 4px 10px; font-size: 12px; border-radius: 6px; }
        .btn-icon { width: 36px; height: 36px; padding: 0; justify-content: center; }

        /* ── Tables ── */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 16px; text-align: left;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            color: var(--text-muted); border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody td {
            padding: 14px 16px; font-size: 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
        }
        tbody tr:hover { background: var(--bg-card-hover); }
        tbody tr:last-child td { border-bottom: none; }

        /* ── Forms ── */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 13px; font-weight: 600;
            color: var(--text-secondary); margin-bottom: 6px;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 10px 14px;
            background: var(--bg-input); border: 1px solid var(--border);
            border-radius: var(--radius-sm); color: var(--text-primary);
            font-size: 14px; font-family: inherit;
            transition: var(--transition);
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(6,182,212,0.15);
        }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 12px; font-weight: 600;
        }
        .badge-success { background: rgba(16,185,129,0.15); color: var(--success); }
        .badge-danger { background: rgba(239,68,68,0.15); color: var(--danger); }
        .badge-warning { background: rgba(245,158,11,0.15); color: var(--warning); }
        .badge-info { background: rgba(59,130,246,0.15); color: var(--info); }
        .badge-primary { background: rgba(99,102,241,0.15); color: var(--primary-light); }
        .badge-accent { background: rgba(6,182,212,0.15); color: var(--accent-light); }

        /* ── Alerts ── */
        .alert {
            padding: 14px 20px; border-radius: var(--radius-sm);
            font-size: 14px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 12px;
        }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: var(--success); }
        .alert-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: var(--danger); }
        .alert-warning { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3); color: var(--warning); }

        /* ── Grid Utilities ── */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
    @yield('head')
</head>
<body>
    @php
        $tenantParam = request()->route('tenant') ?? auth('owner')->user()?->tenant?->slug ?? 'default';
        $owner = auth('owner')->user();
        $isUnlimited = $owner?->isUnlimited() ?? false;
        $balance = $owner?->getTokenBalanceAmount() ?? 0;
    @endphp

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">M</div>
            <div class="sidebar-brand-text">
                <h1>{{ $owner?->organization_name ?? 'MariLMS' }}</h1>
                <span>Owner Panel &bull; v{{ config('app.version', '1.4.0') }}</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Overview</div>
                <a href="{{ route('tenant.owner.dashboard', ['tenant' => $tenantParam]) }}" class="nav-link {{ request()->routeIs('tenant.owner.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Kuis & Peserta</div>
                <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenantParam]) }}" class="nav-link {{ request()->routeIs('tenant.owner.quizzes.*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i> Manajemen Kuis
                </a>
                <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenantParam]) }}" class="nav-link {{ request()->routeIs('tenant.owner.participants.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i> Data Peserta
                </a>
                <a href="{{ route('tenant.owner.reports', ['tenant' => $tenantParam]) }}" class="nav-link {{ request()->routeIs('tenant.owner.reports*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Laporan & Hasil
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Billing & Pengaturan</div>
                <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenantParam]) }}" class="nav-link {{ request()->routeIs('tenant.owner.tokens*') ? 'active' : '' }}">
                    <i class="fas fa-coins"></i> Token & Top-up
                </a>
                <a href="{{ route('tenant.owner.settings', ['tenant' => $tenantParam]) }}" class="nav-link {{ request()->routeIs('tenant.owner.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Pengaturan Tenant
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr($owner?->name ?? 'OW', 0, 2)) }}
                </div>
                <div class="sidebar-user-info">
                    <h4>{{ $owner?->name ?? 'Owner' }}</h4>
                    <span>{{ $owner?->email ?? '' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('owner.logout') }}" style="margin-top: 8px;">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm" style="width: 100%; justify-content: center;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-bar">
            <div class="top-bar-left">
                <button class="btn btn-icon btn-ghost" onclick="document.getElementById('sidebar').classList.toggle('open')" style="display: none;" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h2>@yield('page-title', 'Dashboard')</h2>
                    @hasSection('breadcrumb')
                    <div class="breadcrumb">
                        <a href="{{ route('tenant.owner.dashboard', ['tenant' => $tenantParam]) }}">Home</a>
                        <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                        @yield('breadcrumb')
                    </div>
                    @endif
                </div>
            </div>
            <div class="top-bar-right">
                <div style="display: flex; align-items: center; gap: 12px; background: var(--bg-card); padding: 6px 14px; border-radius: 20px; border: 1px solid var(--border);">
                    <i class="fas fa-coins" style="color: var(--warning); font-size: 16px;"></i>
                    <span style="font-size: 13px; font-weight: 700; color: var(--text-white);">
                        @if($isUnlimited)
                            <span style="color: var(--accent-light);">∞ Unlimited Token</span>
                        @else
                            {{ number_format($balance) }} Token
                        @endif
                    </span>
                    @if(!$isUnlimited)
                    <a href="{{ route('tenant.owner.tokens', ['tenant' => $tenantParam]) }}" class="btn btn-xs btn-accent" style="padding: 2px 8px;">
                        + Top Up
                    </a>
                    @endif
                </div>
                @yield('top-bar-actions')
            </div>
        </header>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('warning') }}
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    @yield('scripts')
    <script>
        if (window.innerWidth <= 1024) {
            document.getElementById('mobile-menu-btn').style.display = 'flex';
        }
        window.addEventListener('resize', () => {
            document.getElementById('mobile-menu-btn').style.display =
                window.innerWidth <= 1024 ? 'flex' : 'none';
        });
    </script>
</body>
</html>
