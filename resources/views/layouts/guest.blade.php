<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @viteReactRefresh
        @vite(['resources/sass/app.scss', 'resources/js/app.jsx'])
    </head>
    <body class="h-100">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="mx-1 col-10 col-sm-8 col-md-6 col-lg-5 col-xl-4 col-xxl-3 bg-dark-subtle bg-gradient py-3 px-3 rounded-3 shadow">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
