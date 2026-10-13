{{-- finance/incomes/create.blade.php / edit.blade.php --}}
@extends('layouts.app')
@section('title', isset($item) ? 'Edit Pemasukan' : 'Tambah Pemasukan')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header">{{ isset($item) ? 'Edit Pemasukan' : 'Tambah Pemasukan' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('finance.incomes.update', $item) : route('finance.incomes.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div><label class="label">No. Pemasukan *</label>
                    <input name="income_number" class="input" value="{{ old('income_number', $item->income_number ?? '') }}" required></div>
                <div><label class="label">Kategori</label>
                    <input name="category" class="input" value="{{ old('category', $item->category ?? '') }}" placeholder="Mis: Penjualan, Lainnya"></div>
                <div><label class="label">Jumlah (Rp) *</label>
                    <input name="amount" type="number" step="0.01" min="0" class="input" value="{{ old('amount', $item->amount ?? '') }}" required></div>
                <div><label class="label">Tanggal</label>
                    <input name="income_date" type="date" class="input" value="{{ old('income_date', isset($item) ? $item->income_date?->toDateString() : now()->toDateString()) }}"></div>
                <div style="grid-column:1/-1"><label class="label">Deskripsi</label>
                    <textarea name="description" class="input" rows="2">{{ old('description', $item->description ?? '') }}</textarea></div>
            </div>
            <div class="form-actions">
                <a href="{{ route('finance.incomes.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection