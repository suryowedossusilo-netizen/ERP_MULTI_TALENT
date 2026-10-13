@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Laporan Penjualan</h1><p class="page-sub">Order selesai per periode</p></div>
</div>

<div class="card" style="margin-bottom:1.25rem">
    <div class="card-body" style="padding:1rem">
        <form method="GET" style="display:flex;gap:.75rem;align-items:end;flex-wrap:wrap">
            <div><label class="label">Dari</label>
                <input type="date" name="start" class="input" value="{{ $start->toDateString() }}"></div>
            <div><label class="label">Sampai</label>
                <input type="date" name="end" class="input" value="{{ $end->toDateString() }}"></div>
            <button class="btn btn-primary"><i class="fa fa-magnifying-glass"></i> Filter</button>
        </form>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-icon" style="background:var(--success-bg);color:var(--success)"><i class="fa fa-cart-shopping"></i></div>
        <div><div class="stat-label">Order Selesai</div><div class="stat-value">{{ $orders->count() }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--primary-soft);color:var(--primary)"><i class="fa fa-sack-dollar"></i></div>
        <div><div class="stat-label">Total Penjualan</div><div class="stat-value">{{ rupiah($orders->sum('total')) }}</div></div></div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>No. Order</th><th>Pelanggan</th><th>Tanggal</th><th style="text-align:right">Subtotal</th><th style="text-align:right">Diskon</th><th style="text-align:right">Pajak</th><th style="text-align:right">Total</th></tr></thead>
            <tbody>
            @forelse($orders as $o)
                <tr>
                    <td style="font-weight:600">{{ $o->order_number }}</td>
                    <td>{{ $o->customer->name ?? '-' }}</td>
                    <td>{{ $o->created_at->format('d/m/Y') }}</td>
                    <td style="text-align:right">{{ rupiah($o->subtotal) }}</td>
                    <td style="text-align:right">{{ rupiah($o->discount) }}</td>
                    <td style="text-align:right">{{ rupiah($o->tax) }}</td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($o->total) }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">Tidak ada data pada periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection