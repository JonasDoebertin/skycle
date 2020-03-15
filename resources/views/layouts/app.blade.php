<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" >
</head>
<body class="bg-gray-100 h-screen antialiased leading-none">

    @include('partials.app.nav')
    @include('partials.app.header')

    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if (flash()->message)
                <div class="text-sm border border-t-8 rounded px-3 py-4 mb-4 {{ flash()->class }}" role="alert">
                    {{ flash()->message }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
