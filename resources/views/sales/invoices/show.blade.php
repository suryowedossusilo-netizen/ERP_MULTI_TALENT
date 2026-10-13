@extends('layouts.app')
@section('title', 'Invoice ' . $invoice->invoice_number)
@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Invoice {{ $invoice->invoice_number }}</h1>
        <p class="page-sub">{{ $invoice->customer->name ?? 'Umum' }} &middot; Terbit {{ $invoice->issue_date->format('d/m/Y') }}</p>
    </div>
    @if($invoice->status !== 'paid')
    <a href="{{ route('sales.payments.create') }}" class="btn btn-primary"><i class="fa fa-money-bill"></i> Catat Pembayaran</a>
    @endif
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-icon" style="background:var(--info-bg);color:var(--info)"><i class="fa fa-file-invoice"></i></div>
        <div><div class="stat-label">Total Tagihan</div><div class="stat-value" style="font-size:1.1rem">{{ rupiah($invoice->amount) }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--success-bg);color:var(--success)"><i class="fa fa-check"></i></div>
        <div><div class="stat-label">Terbayar</div><div class="stat-value" style="font-size:1.1rem">{{ rupiah($invoice->paid) }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--danger-bg);color:var(--danger)"><i class="fa fa-clock"></i></div>
        <div><div class="stat-label">Sisa</div><div class="stat-value" style="font-size:1.1rem">{{ rupiah($invoice->outstanding) }}</div></div></div>
</div>

<div class="card">
    <div class="card-header">Item</div>
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Produk</th><th style="text-align:right">Qty</th><th style="text-align:right">Harga</th><th style="text-align:right">Subtotal</th></tr></thead>
            <tbody>
            @foreach($invoice->order->items ?? [] as $it)
                <tr>
                    <td>{{ $it->product->name ?? '-' }}</td>
                    <td style="text-align:right">{{ $it->quantity }}</td>
                    <td style="text-align:right">{{ rupiah($it->price) }}</td>
                    <td style="text-align:right">{{ rupiah($it->subtotal) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top:1rem">
    <div class="card-header">Riwayat Pembayaran</div>
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>No. Pembayaran</th><th>Tanggal</th><th>Metode</th><th style="text-align:right">Jumlah</th></tr></thead>
            <tbody>
            @forelse($invoice->payments as $pay)
                <tr>
                    <td>{{ $pay->payment_number }}</td>
                    <td>{{ $pay->payment_date->format('d/m/Y') }}</td>
                    <td>{{ $pay->method }}</td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($pay->amount) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Belum ada pembayaran.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection