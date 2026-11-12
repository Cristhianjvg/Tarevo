@php
    $authUser = auth()->user();
    $displayName = $authUser?->nombre ?? $authUser?->username ?? 'Usuario';
    $roleLabel = $authUser?->roles->pluck('nombre')->join(', ') ?: 'Sin rol';

    $menu = [['group' => 'Principal']];

    if (Route::has('dashboard')) {
        $menu[] = [
            'label' => 'Dashboard',
            'url' => route('dashboard'),
            'icon' => 'fa-house',
            'active' => request()->routeIs('dashboard'),
        ];
    }

    $menu[] = ['group' => 'Gestión'];

    if ($authUser?->esSupervisor() && Route::has('personal.index')) {
        $menu[] = [
            'label' => 'Personal',
            'url' => route('personal.index'),
            'icon' => 'fa-users',
            'active' => request()->routeIs('personal.*'),
        ];
    }

    if (Route::has('instalaciones.index')) {
        $menu[] = [
            'label' => 'Instalaciones',
            'url' => route('instalaciones.index'),
            'icon' => 'fa-building',
            'active' => request()->routeIs('instalaciones.*'),
        ];
    }

    if (Route::has('tareas.index')) {
        $menu[] = [
            'label' => 'Tareas',
            'url' => route('tareas.index'),
            'icon' => 'fa-list-check',
            'active' => request()->routeIs('tareas.*'),
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="es" id="root" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title')</title>

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
    <div class="sw-app">

        {{-- TOPBAR --}}
        <header class="sw-topbar">
            <div class="sw-topbar-brand">
                <button type="button" class="sw-btn sw-btn-ghost sw-btn-sm sw-sidebar-toggle"
                        aria-controls="sw-sidebar" aria-expanded="false" aria-label="Abrir menú"
                        onclick="swToggleSidebar(this)">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <span class="sw-topbar-app">
                    <span class="sw-topbar-logo-fallback" aria-hidden="true">
                        <i class="fa-solid fa-list-check"></i>
                    </span>
                </span>

                <div class="sw-topbar-tenant">
                    <span class="sw-topbar-company">{{ config('app.name') }}</span>
                </div>
            </div>

            <div class="sw-topbar-actions">
                <button type="button" class="sw-btn sw-btn-ghost sw-btn-sm"
                        aria-label="Cambiar tema" onclick="swToggleTheme()">
                    <i class="fa-solid fa-circle-half-stroke"></i>
                </button>

                <div class="sw-user-menu">
                    <button type="button" class="sw-user-trigger"
                            aria-haspopup="true" aria-expanded="false"
                            aria-controls="sw-user-dropdown"
                            onclick="swToggleUserMenu(this)">
                        <span class="sw-user-avatar" aria-hidden="true">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <span class="sw-user-name">{{ $displayName }}</span>
                        <i class="fa-solid fa-angle-down sw-user-arrow"></i>
                    </button>

                    <div class="sw-user-dropdown" id="sw-user-dropdown">
                        <div class="sw-user-role">
                            <span class="sw-user-role-label">Rol</span>
                            <span class="sw-user-role-value">{{ $roleLabel }}</span>
                        </div>
                        @if (Route::has('logout'))
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="sw-user-action">
                                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="sw-body">
            {{-- SIDEBAR --}}
            <x-sw-sidebar :items="$menu" :brand="config('app.name')" brand-sub="Gestión de tareas">
                @if (Route::has('logout'))
                    <x-slot:footer>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="sw-sb-link sw-sb-action">
                                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                            </button>
                        </form>
                    </x-slot:footer>
                @endif
            </x-sw-sidebar>

            <button type="button" class="sw-sidebar-backdrop"
                    aria-label="Cerrar menú" onclick="swCloseSidebar()"></button>

            {{-- MAIN --}}
            <main class="sw-main sw-fade-in">
                @yield('content')
            </main>
        </div>

        {{-- FOOTER --}}
        <footer class="sw-footer">
            <span class="sw-footer-brand">
                <a href="{{ url('/') }}" target="_blank">{{ url('/') }}</a> &copy; {{ date('Y') }}
            </span>
        </footer>
    </div>

    <script src="{{ asset('vendor/soffi-ui/soffi-ui.js') }}?v={{ filemtime(public_path('vendor/soffi-ui/soffi-ui.js')) }}"></script>
    @stack('scripts')
</body>
</html>
