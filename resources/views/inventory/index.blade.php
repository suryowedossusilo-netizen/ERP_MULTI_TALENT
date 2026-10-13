@extends('layouts.app')
@section('title', 'Stok Barang')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Stok Barang</h1><p class="page-sub">Stok per produk per gudang</p></div>
    <a href="{{ route('inventory.adjust') }}" class="btn btn-primary"><i class="fa fa-sliders"></i> Penyesuaian Stok</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Produk</th><th>SKU</th><th>Gudang</th><th style="text-align:right">Stok</th></tr></thead>
            <tbody>
            @forelse($stocks as $s)
                <tr>
                    <td style="font-weight:600">{{ $s->product->name ?? '-' }}</td>
                    <td>{{ $s->product->sku ?? '-' }}</td>
                    <td>{{ $s->warehouse->name ?? '-' }}</td>
                    <td style="text-align:right">
                        <span class="badge {{ $s->quantity <= ($s->product->min_stock ?? 0) ? 'badge-danger' : 'badge-success' }}">{{ $s->quantity }}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Belum ada data stok.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $stocks->links() }}</div>
</div>
@endsection