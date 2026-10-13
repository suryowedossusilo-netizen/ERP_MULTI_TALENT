@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-sub">Ringkasan aktivitas bisnis Anda hari ini</p>
    </div>
    <div style="display:flex;gap:.5rem">
        <a href="{{ route('sales.orders.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Order Baru</a>
        <a href="{{ route('inventory.adjust') }}" class="btn btn-secondary"><i class="fa fa-sliders"></i> Sesuaikan Stok</a>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--primary-soft);color:var(--primary)"><i class="fa fa-box"></i></div>
        <div><div class="stat-label">Produk</div><div class="stat-value">{{ $stats['products'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--info-bg);color:var(--info)"><i class="fa fa-users"></i></div>
        <div><div class="stat-label">Pelanggan</div><div class="stat-value">{{ $stats['customers'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--warning-bg);color:var(--warning)"><i class="fa fa-cart-shopping"></i></div>
        <div><div class="stat-label">Order</div><div class="stat-value">{{ $stats['orders'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--success-bg);color:var(--success)"><i class="fa fa-sack-dollar"></i></div>
        <div><div class="stat-label">Pendapatan</div><div class="stat-value">{{ rupiah($stats['revenue']) }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--danger-bg);color:var(--danger)"><i class="fa fa-file-invoice"></i></div>
        <div><div class="stat-label">Piutang</div><div class="stat-value">{{ rupiah($stats['outstanding']) }}</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span><i class="fa fa-clock-rotate-left"></i> Order Terbaru</span>
        <a href="{{ route('sales.orders.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>No. Order</th><th>Pelanggan</th><th>Status</th><th style="text-align:right">Total</th></tr></thead>
            <tbody>
            @forelse($recentOrders as $o)
                <tr>
                    <td><a href="{{ route('sales.orders.show', $o) }}" style="color:var(--primary);font-weight:600">{{ $o->order_number }}</a></td>
                    <td>{{ $o->customer->name ?? '-' }}</td>
                    <td>
                        @php $cls = ['completed'=>'badge-success','cancelled'=>'badge-danger'][$o->status] ?? 'badge-neutral'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($o->status) }}</span>
                    </td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($o->total) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Belum ada order.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection