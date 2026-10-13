@extends('layouts.app')
@section('title', 'Tambah Role')
@section('content')
<div class="card" style="max-width:520px">
    <div class="card-header">Tambah Role Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            <div style="margin-bottom:1rem">
                <label class="label">Nama Role *</label>
                <input name="name" class="input" value="{{ old('name') }}" placeholder="Mis: Admin, Kasir, Gudang" required autofocus>
            </div>
            <div class="form-actions">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection