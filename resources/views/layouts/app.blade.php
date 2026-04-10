<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'LEEDCOURSE')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font pro -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;

            /* background global */
            background: 
                linear-gradient(rgba(255,255,255,0.4), rgba(255,255,255,0.4)),
                url('{{ asset("images/image.png") }}') no-repeat center center fixed;
            background-size: cover;
        }

        .page-container {
            min-height: 100vh;
            background-color: rgba(255,255,255,0.85); /* voile blanc pour lisibilité */
            padding: 16px;
            overflow-x: hidden;
        }

        @media (max-width: 640px) {
            body {
                background-attachment: scroll;
            }

            .page-container {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow rounded-xl mb-6">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        @isset($slot)
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        @else
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        @endisset
    </div>

    @include('partials.password-toggle')
    <script src="{{ mix('js/app.js') }}" defer></script>

    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
