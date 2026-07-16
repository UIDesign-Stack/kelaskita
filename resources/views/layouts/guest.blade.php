<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kelaskita') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="w-100 px-3" style="max-width: 420px;">
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none fs-3 fw-bold text-dark">
                    {{ config('app.name', 'Kelaskita') }}
                </a>
            </div>
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>