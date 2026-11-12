<!DOCTYPE html>
<html lang="es" id="root" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Acceso')</title>

    {{-- Anti-flicker: aplica el tema guardado antes del primer paint --}}
    <script>
        (function () {
            var t = localStorage.getItem('theme');
            if (t) document.documentElement.dataset.theme = t;
        })();
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/soffi-ui/soffi-ui.css') }}?v={{ filemtime(public_path('vendor/soffi-ui/soffi-ui.css')) }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @yield('content')

    <script src="{{ asset('vendor/soffi-ui/soffi-ui.js') }}?v={{ filemtime(public_path('vendor/soffi-ui/soffi-ui.js')) }}"></script>
    @stack('scripts')
</body>
</html>
