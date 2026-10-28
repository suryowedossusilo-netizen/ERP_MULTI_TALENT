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
    <link rel="stylesheet" href="{{ asset('css/erp.css') }}">
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
    // Sidebar toggle: drawer on smaller screens, compact sidebar on desktop.
    const sidebarToggle = document.querySelector('.hamburger');
    const isMobileSidebar = () => window.matchMedia('(max-width: 1024px)').matches;

    sidebarToggle?.addEventListener('click', () => {
        if (isMobileSidebar()) {
            document.body.classList.toggle('sidebar-open');
        } else {
            document.body.classList.toggle('sidebar-collapsed');
        }
    });

    // Close the mobile drawer when clicking outside it or choosing a navigation link.
    document.addEventListener('click', event => {
        if (!isMobileSidebar() || !document.body.classList.contains('sidebar-open')) return;
        const sidebar = document.querySelector('.sidebar');
        if (sidebar && !sidebar.contains(event.target) && !sidebarToggle?.contains(event.target)) {
            document.body.classList.remove('sidebar-open');
        }
    });
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (isMobileSidebar()) document.body.classList.remove('sidebar-open');
        });
    });

    // Keep the appropriate sidebar state when crossing the responsive breakpoint.
    window.addEventListener('resize', () => {
        if (isMobileSidebar()) {
            document.body.classList.remove('sidebar-collapsed');
        } else {
            document.body.classList.remove('sidebar-open');
        }
    });

    // Toggle user dropdown; support keyboard activation too.
    const chip = document.querySelector('.user-chip');
    const menu = document.querySelector('.user-menu');
    const toggleUserMenu = event => {
        event.stopPropagation();
        menu?.classList.toggle('open');
    };
    chip?.addEventListener('click', toggleUserMenu);
    chip?.addEventListener('keydown', event => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            toggleUserMenu(event);
        }
    });
    document.addEventListener('click', () => menu?.classList.remove('open'));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            menu?.classList.remove('open');
            document.body.classList.remove('sidebar-open');
        }
    });

    // Auto-hide alerts.
    setTimeout(() => document.querySelectorAll('.alert').forEach(alert => {
        alert.style.transition = 'opacity .5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }), 4000);
</script>
@stack('scripts')
</body>
</html>