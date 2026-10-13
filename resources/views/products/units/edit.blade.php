@extends('layouts.app')
@section('title', 'Edit Satuan')
@section('content')
<div class="card" style="max-width:520px">
    <div class="card-header">Edit Satuan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('units.update', $item) }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div><label class="label">Nama Satuan *</label>
                    <input name="name" class="input" value="{{ old('name', $item->name) }}" required autofocus></div>
                <div><label class="label">Singkatan</label>
                    <input name="abbreviation" class="input" value="{{ old('abbreviation', $item->abbreviation) }}"></div>
            </div>
            <div class="form-actions">
                <a href="{{ route('units.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection