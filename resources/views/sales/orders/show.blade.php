@extends('layouts.app')
@section('title', 'Detail Order')
@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Order {{ $order->order_number }}</h1>
        <p class="page-sub">{{ $order->created_at->format('d F Y H:i') }} &middot; Pelanggan: {{ $order->customer->name ?? 'Umum' }}</p>
    </div>
    <div style="display:flex;gap:.5rem">
        @if($order->status !== 'completed')
        <form method="POST" action="{{ route('sales.orders.confirm', $order) }}" onsubmit="return confirm('Konfirmasi order & kurangi stok?')">
            @csrf
            <button class="btn btn-primary"><i class="fa fa-check"></i> Konfirmasi Order</button>
        </form>
        @endif
        <form method="POST" action="{{ route('sales.orders.destroy', $order) }}" onsubmit="return confirm('Hapus order ini?')">
            @csrf @method('DELETE')<button class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
        </form>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-icon" style="background:#e2e8f0;color:#475569"><i class="fa fa-flag"></i></div>
        <div><div class="stat-label">Status</div>
        <div>@php $cls = ['completed'=>'badge-success','cancelled'=>'badge-danger'][$order->status] ?? 'badge-warning'; @endphp
        <span class="badge {{ $cls }}">{{ ucfirst($order->status) }}</span></div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--bg);color:var(--text)"><i class="fa fa-calculator"></i></div>
        <div><div class="stat-label">Subtotal</div><div class="stat-value" style="font-size:1.05rem">{{ rupiah($order->subtotal) }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--warning-bg);color:var(--warning)"><i class="fa fa-percent"></i></div>
        <div><div class="stat-label">Diskon + Pajak</div><div class="stat-value" style="font-size:1.05rem">{{ rupiah($order->discount + $order->tax) }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--success-bg);color:var(--success)"><i class="fa fa-sack-dollar"></i></div>
        <div><div class="stat-label">Total</div><div class="stat-value" style="font-size:1.05rem;color:var(--primary)">{{ rupiah($order->total) }}</div></div></div>
</div>

<div class="card">
    <div class="card-header">Item Order</div>
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Produk</th><th style="text-align:right">Qty</th><th style="text-align:right">Harga</th><th style="text-align:right">Subtotal</th></tr></thead>
            <tbody>
            @foreach($order->items as $it)
                <tr>
                    <td style="font-weight:600">{{ $it->product->name ?? '-' }}</td>
                    <td style="text-align:right">{{ $it->quantity }}</td>
                    <td style="text-align:right">{{ rupiah($it->price) }}</td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($it->subtotal) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@if($order->note)
<div class="card" style="margin-top:1rem"><div class="card-body" style="color:var(--muted);font-size:.875rem">
    <strong>Catatan:</strong> {{ $order->note }}
</div></div>
@endif
@endsection