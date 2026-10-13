@extends('layouts.app')
@section('title', isset($item) ? 'Edit PO' : 'Buat PO')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header">{{ isset($item) ? 'Edit Purchase Order' : 'Buat Purchase Order' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('purchasing.purchase-orders.update', $item) : route('purchasing.purchase-orders.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div><label class="label">No. PO *</label>
                    <input name="po_number" class="input" value="{{ old('po_number', $item->po_number ?? '') }}" required></div>
                <div><label class="label">Tanggal Order</label>
                    <input name="order_date" type="date" class="input" value="{{ old('order_date', isset($item) ? $item->order_date?->toDateString() : now()->toDateString()) }}"></div>
                <div><label class="label">Pajak (Rp)</label>
                    <input name="tax" type="number" step="0.01" min="0" class="input" value="{{ old('tax', $item->tax ?? 0) }}"></div>
                <div style="grid-column:1/-1"><label class="label">Catatan</label>
                    <textarea name="note" class="input" rows="2">{{ old('note', $item->note ?? '') }}</textarea></div>
            </div>
            <div class="form-actions">
                <a href="{{ route('purchasing.purchase-orders.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection