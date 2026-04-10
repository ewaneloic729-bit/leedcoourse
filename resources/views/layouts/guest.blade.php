<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .guest-pro-body {
                min-height: 100vh;
                margin: 0;
                background: radial-gradient(140% 130% at 0% 0%, rgba(22, 163, 74, 0.22), rgba(2, 6, 23, 0.96)),
                            url('{{ asset("images/image.png") }}') center/cover no-repeat fixed;
            }

            .guest-pro-shell {
                min-height: 100vh;
            }

            .guest-auth-wrapper {
                min-height: 100vh;
                padding: 1.5rem;
            }

            .guest-auth-card {
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.99), #ffffff);
                border: 1px solid rgba(148, 163, 184, 0.28);
                box-shadow: 0 24px 54px rgba(2, 6, 23, 0.35);
                border-radius: 1rem;
            }

            .guest-auth-logo {
                margin-bottom: 0.75rem;
            }

            .guest-auth-logo a {
                text-decoration: none;
            }
        </style>
    </head>
    <body class="guest-pro-body">

        <div class="font-sans text-gray-900 antialiased guest-pro-shell">
            {{ $slot }}
        </div>
        @include('partials.password-toggle')
        <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
