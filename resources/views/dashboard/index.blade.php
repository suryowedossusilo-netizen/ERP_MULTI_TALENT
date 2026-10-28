@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-head dashboard-hero">
    <div>
        <p class="dashboard-eyebrow">ENTERPRISE CONTROL CENTER</p>
        <h1 class="page-title">Dashboard Analytics</h1>
        <p class="page-sub">Ringkasan operasional tenant Anda dalam satu tampilan.</p>
    </div>
    <div class="dashboard-actions">
        <a href="{{ route('sales.orders.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Order Baru</a>
        <a href="{{ route('inventory.adjust') }}" class="btn btn-secondary"><i class="fa fa-sliders"></i> Sesuaikan Stok</a>
    </div>
</div>

<div class="metrics-grid">
    <div class="glass-card metric-card">
        <div class="metric-info">
            <div class="title">Total Produk</div>
            <div class="value">{{ number_format($stats['products'] ?? 0) }}</div>
            <div class="metric-caption">Produk terdaftar</div>
        </div>
        <div class="metric-icon"><i class="fa fa-boxes-stacked" aria-hidden="true"></i></div>
    </div>
    <div class="glass-card metric-card">
        <div class="metric-info">
            <div class="title">Total Pelanggan</div>
            <div class="value">{{ number_format($stats['customers'] ?? 0) }}</div>
            <div class="metric-caption">Pelanggan terdaftar</div>
        </div>
        <div class="metric-icon metric-icon-purple"><i class="fa fa-users" aria-hidden="true"></i></div>
    </div>
    <div class="glass-card metric-card">
        <div class="metric-info">
            <div class="title">Total Order</div>
            <div class="value">{{ number_format($stats['orders'] ?? 0) }}</div>
            <div class="metric-caption">Seluruh order tenant</div>
        </div>
        <div class="metric-icon metric-icon-blue"><i class="fa fa-cart-shopping" aria-hidden="true"></i></div>
    </div>
    <div class="glass-card metric-card">
        <div class="metric-info">
            <div class="title">Pendapatan</div>
            <div class="value">{{ rupiah($stats['revenue'] ?? 0) }}</div>
            <div class="metric-caption">Invoice berstatus lunas</div>
        </div>
        <div class="metric-icon metric-icon-green"><i class="fa fa-sack-dollar" aria-hidden="true"></i></div>
    </div>
    <div class="glass-card metric-card">
        <div class="metric-info">
            <div class="title">Piutang</div>
            <div class="value">{{ rupiah($stats['outstanding'] ?? 0) }}</div>
            <div class="metric-caption">Invoice belum lunas</div>
        </div>
        <div class="metric-icon metric-icon-amber"><i class="fa fa-file-invoice-dollar" aria-hidden="true"></i></div>
    </div>
</div>

<div class="glass-card dashboard-table-card">
    <div class="dashboard-section-heading">
        <div>
            <p class="dashboard-eyebrow">AKTIVITAS TERBARU</p>
            <h2>Order Terbaru</h2>
            <p class="page-sub">Pantau order terbaru yang tercatat pada tenant ini.</p>
        </div>
        <a href="{{ route('sales.orders.index') }}" class="btn btn-secondary btn-sm">Lihat Semua <i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="glass-table-container">
        <table class="glass-table">
            <thead>
                <tr><th>No. Order</th><th>Pelanggan</th><th>Status</th><th class="dashboard-amount">Total</th></tr>
            </thead>
            <tbody>
            @forelse($recentOrders as $o)
                <tr>
                    <td><a class="dashboard-order-link" href="{{ route('sales.orders.show', $o) }}">{{ $o->order_number }}</a></td>
                    <td>{{ $o->customer->name ?? '-' }}</td>
                    <td>
                        @php $cls = ['completed'=>'badge-success','cancelled'=>'badge-danger'][$o->status] ?? 'badge-neutral'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($o->status) }}</span>
                    </td>
                    <td class="dashboard-amount">{{ rupiah($o->total) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Belum ada order. Order baru akan muncul di sini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
