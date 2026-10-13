@extends('layouts.app')
@section('title', 'Tambah Departemen')
@section('content')
<div class="card" style="max-width:520px">
    <div class="card-header">Tambah Departemen Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf
            <div style="margin-bottom:1rem">
                <label class="label">Nama Departemen *</label>
                <input name="name" class="input" value="{{ old('name') }}" placeholder="Mis: IT, Keuangan, Operasional" required autofocus>
            </div>
            <div class="form-actions">
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection