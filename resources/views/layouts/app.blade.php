<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kelaskita') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        @include('layouts.navigation')

        <div class="main-content">
            <nav class="navbar navbar-light bg-white border-bottom px-3">
                <button class="btn btn-outline-secondary d-md-none" type="button"
                    onclick="document.querySelector('.sidebar').classList.toggle('show')">
                    ☰
                </button>
                <span class="navbar-text fw-semibold">@yield('title', 'Dashboard')</span>
                <div class="dropdown ms-auto">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            @isset($header)
                <div class="bg-white border-bottom px-4 py-3">
                    {{ $header }}
                </div>
            @endisset

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>