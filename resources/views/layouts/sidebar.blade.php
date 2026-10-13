<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo"><i class="fa fa-cubes"></i></div>
        <div>{{ tenant()?->name ?? config('app.name') }}</div>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa fa-gauge-high"></i> Dashboard
        </a>

        <div class="nav-group-title">Penjualan</div>
        <a href="{{ route('sales.orders.index') }}" class="nav-link {{ request()->routeIs('sales.orders.*') ? 'active' : '' }}">
            <i class="fa fa-cart-shopping"></i> Order
        </a>
        <a href="{{ route('sales.invoices.index') }}" class="nav-link {{ request()->routeIs('sales.invoices.*') ? 'active' : '' }}">
            <i class="fa fa-file-invoice"></i> Invoice
        </a>
        <a href="{{ route('sales.payments.index') }}" class="nav-link {{ request()->routeIs('sales.payments.*') ? 'active' : '' }}">
            <i class="fa fa-money-bill-wave"></i> Pembayaran
        </a>

        <div class="nav-group-title">Pembelian</div>
        <a href="{{ route('purchasing.purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchasing.*') ? 'active' : '' }}">
            <i class="fa fa-truck-field"></i> Purchase Order
        </a>

        <div class="nav-group-title">Produk</div>
        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fa fa-box"></i> Produk
        </a>
        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fa fa-tags"></i> Kategori
        </a>
        <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
            <i class="fa fa-ruler-combined"></i> Satuan
        </a>

        <div class="nav-group-title">Inventaris</div>
        <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
            <i class="fa fa-warehouse"></i> Stok
        </a>
        <a href="{{ route('inventory.adjust') }}" class="nav-link {{ request()->routeIs('inventory.adjust') ? 'active' : '' }}">
            <i class="fa fa-sliders"></i> Penyesuaian
        </a>
        <a href="{{ route('inventory.movements') }}" class="nav-link {{ request()->routeIs('inventory.movements') ? 'active' : '' }}">
            <i class="fa fa-right-left"></i> Pergerakan
        </a>
        <a href="{{ route('warehouses.index') }}" class="nav-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
            <i class="fa fa-building"></i> Gudang
        </a>

        <div class="nav-group-title">Relasi</div>
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="fa fa-users"></i> Pelanggan
        </a>
        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="fa fa-handshake"></i> Pemasok
        </a>

        <div class="nav-group-title">SDM</div>
        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="fa fa-id-card"></i> Karyawan
        </a>
        <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
            <i class="fa fa-sitemap"></i> Departemen
        </a>
        <a href="{{ route('positions.index') }}" class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}">
            <i class="fa fa-briefcase"></i> Jabatan
        </a>

        <div class="nav-group-title">Keuangan</div>
        <a href="{{ route('finance.cashflow') }}" class="nav-link {{ request()->routeIs('finance.cashflow') ? 'active' : '' }}">
            <i class="fa fa-scale-balanced"></i> Arus Kas
        </a>
        <a href="{{ route('finance.incomes.index') }}" class="nav-link {{ request()->routeIs('finance.incomes.*') ? 'active' : '' }}">
            <i class="fa fa-arrow-trend-up"></i> Pemasukan
        </a>
        <a href="{{ route('finance.expenses.index') }}" class="nav-link {{ request()->routeIs('finance.expenses.*') ? 'active' : '' }}">
            <i class="fa fa-arrow-trend-down"></i> Pengeluaran
        </a>

        <div class="nav-group-title">Analisis</div>
        <a href="{{ route('reports.sales') }}" class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
            <i class="fa fa-chart-line"></i> Penjualan
        </a>
        <a href="{{ route('reports.inventory') }}" class="nav-link {{ request()->routeIs('reports.inventory') ? 'active' : '' }}">
            <i class="fa fa-chart-pie"></i> Inventaris
        </a>
        <a href="{{ route('reports.finance') }}" class="nav-link {{ request()->routeIs('reports.finance') ? 'active' : '' }}">
            <i class="fa fa-chart-column"></i> Keuangan
        </a>

        <div class="nav-group-title">Sistem</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fa fa-user-gear"></i> Pengguna
        </a>
        <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="fa fa-user-shield"></i> Role
        </a>
        <a href="{{ route('settings.company.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="fa fa-gear"></i> Pengaturan
        </a>
        @if(auth()->user()?->hasPermission('manage-tenants'))
        <a href="{{ route('tenants.index') }}" class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
            <i class="fa fa-building-columns"></i> Tenant
        </a>
        @endif
    </nav>
    <div class="sidebar-footer">v1.0 &middot; {{ config('app.name') }}</div>
</aside>