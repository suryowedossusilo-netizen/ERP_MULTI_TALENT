@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('content')
<div class="card" style="max-width:520px">
    <div class="card-header">Tambah Kategori Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div style="margin-bottom:1rem">
                <label class="label">Nama Kategori *</label>
                <input name="name" class="input" value="{{ old('name') }}" placeholder="Mis: Elektronik, Pakaian, Makanan" required autofocus>
            </div>
            <div class="form-actions">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection