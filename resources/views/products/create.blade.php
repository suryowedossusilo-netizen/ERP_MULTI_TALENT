@extends('layouts.app')
@section('title', isset($item) ? 'Edit Produk' : 'Tambah Produk')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header">{{ isset($item) ? 'Edit Produk' : 'Tambah Produk Baru' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('products.update', $item) : route('products.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div><label class="label">SKU *</label>
                    <input name="sku" class="input" value="{{ old('sku', $item->sku ?? '') }}" required></div>
                <div><label class="label">Barcode</label>
                    <input name="barcode" class="input" value="{{ old('barcode', $item->barcode ?? '') }}"></div>
                <div style="grid-column:1/-1"><label class="label">Nama Produk *</label>
                    <input name="name" class="input" value="{{ old('name', $item->name ?? '') }}" required></div>
                <div><label class="label">Harga Beli</label>
                    <input name="purchase_price" type="number" step="0.01" class="input" value="{{ old('purchase_price', $item->purchase_price ?? '') }}"></div>
                <div><label class="label">Harga Jual *</label>
                    <input name="selling_price" type="number" step="0.01" class="input" value="{{ old('selling_price', $item->selling_price ?? '') }}" required></div>
                <div><label class="label">Stok Minimum</label>
                    <input name="min_stock" type="number" class="input" value="{{ old('min_stock', $item->min_stock ?? 0) }}"></div>
                <div><label class="label">Status</label>
                    <select name="is_active" class="select">
                        <option value="1" {{ old('is_active', $item->is_active ?? 1) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $item->is_active ?? 1) ? '' : 'selected' }}>Nonaktif</option>
                    </select></div>
            </div>
            <div class="form-actions">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection