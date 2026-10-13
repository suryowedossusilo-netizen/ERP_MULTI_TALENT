@extends('layouts.app')
@section('title', 'Laporan Inventaris')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Laporan Inventaris</h1><p class="page-sub">Nilai aset stok per gudang</p></div>
</div>
<div class="stat-grid">
    <div class="stat-card"><div class="stat-icon" style="background:var(--primary-soft);color:var(--primary)"><i class="fa fa-warehouse"></i></div>
        <div><div class="stat-label">Total Nilai Stok</div><div class="stat-value">{{ rupiah($totalValue) }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--info-bg);color:var(--info)"><i class="fa fa-boxes-stacked"></i></div>
        <div><div class="stat-label">Total Unit</div><div class="stat-value">{{ $stocks->sum('quantity') }}</div></div></div>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Produk</th><th>Kategori</th><th>Gudang</th><th style="text-align:right">Qty</th><th style="text-align:right">Harga Beli</th><th style="text-align:right">Nilai</th></tr></thead>
            <tbody>
            @forelse($stocks as $s)
                <tr>
                    <td style="font-weight:600">{{ $s->product->name ?? '-' }}</td>
                    <td>{{ $s->product->category->name ?? '-' }}</td>
                    <td>{{ $s->warehouse->name ?? '-' }}</td>
                    <td style="text-align:right">{{ $s->quantity }}</td>
                    <td style="text-align:right">{{ rupiah($s->product->purchase_price ?? 0) }}</td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($s->quantity * ($s->product->purchase_price ?? 0)) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Belum ada stok.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection