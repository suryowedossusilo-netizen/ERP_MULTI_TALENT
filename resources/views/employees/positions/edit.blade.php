@extends('layouts.app')
@section('title', 'Edit Jabatan')
@section('content')
<div class="card" style="max-width:520px">
    <div class="card-header">Edit Jabatan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('positions.update', $item) }}">
            @csrf
            @method('PUT')
            <div style="margin-bottom:1rem">
                <label class="label">Nama Jabatan *</label>
                <input name="name" class="input" value="{{ old('name', $item->name) }}" required autofocus>
            </div>
            <div class="form-actions">
                <a href="{{ route('positions.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection