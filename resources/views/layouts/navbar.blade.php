<header class="topbar">
    <button class="hamburger"><i class="fa fa-bars"></i></button>
    <div class="topbar-title">@yield('title', 'Dashboard')</div>
    <div class="topbar-search">
        <i class="fa fa-magnifying-glass"></i>
        <input type="text" placeholder="Cari data... (Ctrl+K)">
    </div>
    <div style="flex:1"></div>
    <div class="user-chip">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}</div>
        <div>
            <div class="name">{{ auth()->user()?->name }}</div>
            <div class="email">{{ auth()->user()?->email }}</div>
        </div>
        <i class="fa fa-chevron-down" style="font-size:.7rem;color:var(--muted)"></i>
        <div class="user-menu">
            <a href="{{ route('settings.company.edit') }}"><i class="fa fa-gear"></i> Pengaturan</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><i class="fa fa-right-from-bracket"></i> Keluar</button>
            </form>
        </div>
    </div>
</header>