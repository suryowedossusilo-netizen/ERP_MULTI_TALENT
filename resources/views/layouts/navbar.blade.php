<header class="topbar erp-header">
    <button class="hamburger" type="button" aria-label="Buka atau tutup navigasi" aria-controls="erpSidebar">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </button>

    <div class="topbar-title">@yield('title', 'Overview')</div>

    <div class="topbar-search erp-search-bar">
        <i class="fa fa-magnifying-glass" aria-hidden="true"></i>
        <input
            class="erp-search-input"
            type="search"
            placeholder="Cari operasional, tenant..."
            aria-label="Cari data"
        >
    </div>

    <div style="flex:1"></div>

    <div class="user-chip" role="button" tabindex="0" aria-label="Menu akun pengguna">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'AD', 0, 2)) }}</div>
        <div class="user-details">
            <div class="name">{{ auth()->user()?->name ?? 'Administrator' }}</div>
            <div class="email">{{ auth()->user()?->email ?? '' }}</div>
        </div>
        <i class="fa fa-chevron-down user-menu-chevron" aria-hidden="true"></i>

        <div class="user-menu">
            <a href="{{ route('settings.company.edit') }}"><i class="fa fa-gear"></i> Pengaturan</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><i class="fa fa-right-from-bracket"></i> Keluar</button>
            </form>
        </div>
    </div>
</header>
