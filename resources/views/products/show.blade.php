@extends('layouts.app')
@section('title', 'Detail Produk')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">{{ $item->name }}</h1><p class="page-sub">SKU: {{ $item->sku }}</p></div>
    <a href="{{ route('products.edit', $item) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a>
</div>
<div class="card" style="max-width:640px">
    <div class="card-body">
        <div class="detail-row"><span class="k">Barcode</span><span>{{ $item->barcode ?? '-' }}</span></div>
        <div class="detail-row"><span class="k">Kategori</span><span>{{ $item->category->name ?? '-' }}</span></div>
        <div class="detail-row"><span class="k">Satuan</span><span>{{ $item->unit->name ?? '-' }}</span></div>
        <div class="detail-row"><span class="k">Harga Beli</span><span>{{ rupiah($item->purchase_price) }}</span></div>
        <div class="detail-row"><span class="k">Harga Jual</span><span style="font-weight:700">{{ rupiah($item->selling_price) }}</span></div>
        <div class="detail-row"><span class="k">Total Stok</span><span><span class="badge badge-info">{{ $item->total_stock ?? 0 }}</span></span></div>
        <div class="detail-row"><span class="k">Status</span>
            <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
    </div>
</div>
@endsection