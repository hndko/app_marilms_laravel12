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

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Vite Assets (Tailwind CSS v4 & JS loaded locally, no CDN) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });
        });
    </script>
</head>

<body class="h-full bg-white text-gray-800 dark:bg-gray-900 dark:text-gray-200 font-sans antialiased">
    @yield('content')

    @stack('scripts')
</body>

</html>
