@extends('layouts.app')
@section('title', isset($item) ? 'Edit Pelanggan' : 'Tambah Pelanggan')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header">{{ isset($item) ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('customers.update', $item) : route('customers.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="form-grid">
                <div><label class="label">Kode *</label>
                    <input name="code" class="input" value="{{ old('code', $item->code ?? '') }}" required></div>
                <div><label class="label">Nama *</label>
                    <input name="name" class="input" value="{{ old('name', $item->name ?? '') }}" required></div>
                <div><label class="label">Email</label>
                    <input name="email" type="email" class="input" value="{{ old('email', $item->email ?? '') }}"></div>
                <div><label class="label">Telepon</label>
                    <input name="phone" class="input" value="{{ old('phone', $item->phone ?? '') }}"></div>
                <div><label class="label">Limit Kredit</label>
                    <input name="credit_limit" type="number" step="0.01" class="input" value="{{ old('credit_limit', $item->credit_limit ?? 0) }}"></div>
                <div style="grid-column:1/-1"><label class="label">Alamat</label>
                    <textarea name="address" class="input" rows="2">{{ old('address', $item->address ?? '') }}</textarea></div>
            </div>
            <div class="form-actions">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection