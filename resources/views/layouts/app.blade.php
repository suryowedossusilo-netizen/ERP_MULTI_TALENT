<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @php
        $erpCssUrl = file_exists(public_path('css/erp.css')) ? config('app.url') . '/css/erp.css' : null;
    @endphp
    @if ($erpCssUrl)
        <link rel="stylesheet" href="{{ $erpCssUrl }}">
    @endif
</head>
<body>
<div class="layout">
    @include('layouts.sidebar')
    <div class="main">
        @include('layouts.navbar')
        <main class="content">
            @include('layouts.alerts')
            @yield('content')
        </main>
        @include('layouts.footer')
    </div>
</div>
<script>
    // Toggle sidebar (mobile)
    document.querySelector('.hamburger')?.addEventListener('click', () => {
        document.body.classList.toggle('sidebar-open');
    });
    // Toggle user dropdown
    const chip = document.querySelector('.user-chip');
    const menu = document.querySelector('.user-menu');
    chip?.addEventListener('click', e => { e.stopPropagation(); menu?.classList.toggle('open'); });
    document.addEventListener('click', () => menu?.classList.remove('open'));
    // Auto-hide alert
    setTimeout(() => document.querySelectorAll('.alert').forEach(a => {
        a.style.transition = 'opacity .5s'; a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
    }), 4000);
</script>
@stack('scripts')
</body>
</html>