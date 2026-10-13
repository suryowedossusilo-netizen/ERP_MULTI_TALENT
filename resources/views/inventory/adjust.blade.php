@extends('layouts.app')
@section('title', 'Penyesuaian Stok')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header">Sesuaikan Stok Manual</div>
    <div class="card-body">
        <form method="POST" action="{{ url('/inventory/adjust') }}">
            @csrf
            <div class="form-grid">
                <div style="grid-column:1/-1"><label class="label">Produk *</label>
                    <select name="product_id" class="select" required>
                        <option value="">— Pilih Produk —</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->sku }} — {{ $p->name }}</option>
                        @endforeach
                    </select></div>
                <div style="grid-column:1/-1"><label class="label">Gudang *</label>
                    <select name="warehouse_id" class="select" required>
                        <option value="">— Pilih Gudang —</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select></div>
                <div><label class="label">Jenis *</label>
                    <select name="type" class="select" required>
                        <option value="in">Stok Masuk (+)</option>
                        <option value="out">Stok Keluar (−)</option>
                    </select></div>
                <div><label class="label">Jumlah *</label>
                    <input name="quantity" type="number" step="0.001" min="0.001" class="input" required></div>
                <div style="grid-column:1/-1"><label class="label">Catatan</label>
                    <textarea name="note" class="input" rows="2" placeholder="Alasan penyesuaian..."></textarea></div>
            </div>
            <div class="form-actions">
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection