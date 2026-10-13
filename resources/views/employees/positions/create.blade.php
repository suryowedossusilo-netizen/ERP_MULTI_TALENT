@extends('layouts.app')
@section('title', 'Tambah Jabatan')
@section('content')
<div class="card" style="max-width:520px">
    <div class="card-header">Tambah Jabatan Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('positions.store') }}">
            @csrf
            <div style="margin-bottom:1rem">
                <label class="label">Nama Jabatan *</label>
                <input name="name" class="input" value="{{ old('name') }}" placeholder="Mis: Staff, Supervisor, Manager" required autofocus>
            </div>
            <div class="form-actions">
                <a href="{{ route('positions.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection