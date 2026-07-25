<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo/logo-icon.svg') }}?v=1.5.1">
    <link rel="shortcut icon" href="{{ asset('images/logo/logo-icon.svg') }}?v=1.5.1">

    <!-- Vite Assets (Tailwind CSS v4 & JS loaded locally, no CDN) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-slate-50 text-slate-900 font-sans antialiased">
    @yield('content')

    @stack('scripts')
</body>

</html>
