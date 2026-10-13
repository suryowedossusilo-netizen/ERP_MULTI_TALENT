@extends('layouts.app')
@section('title', 'Pengaturan Perusahaan')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header">Profil Perusahaan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('settings.company.update') }}">
            @csrf @method('PUT')
            <div class="form-grid">
                <div style="grid-column:1/-1"><label class="label">Nama Perusahaan *</label>
                    <input name="company_name" class="input" value="{{ old('company_name', $setting->company_name) }}" required></div>
                <div><label class="label">Telepon</label>
                    <input name="phone" class="input" value="{{ old('phone', $setting->phone) }}"></div>
                <div><label class="label">Email</label>
                    <input name="email" type="email" class="input" value="{{ old('email', $setting->email) }}"></div>
                <div><label class="label">NPWP</label>
                    <input name="tax_number" class="input" value="{{ old('tax_number', $setting->tax_number) }}"></div>
                <div style="grid-column:1/-1"><label class="label">Alamat</label>
                    <textarea name="address" class="input" rows="2">{{ old('address', $setting->address) }}</textarea></div>
            </div>
            <div class="form-actions"><button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button></div>
        </form>
    </div>
</div>
@endsection