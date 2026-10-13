@extends('layouts.app')
@section('title', 'Tampilan')
@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header">Personalisasi Tampilan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('settings.appearance.update') }}">
            @csrf @method('PUT')
            <div class="form-grid">
                <div><label class="label">Warna Utama</label>
                    <input name="theme[primary_color]" type="color" class="input" style="height:44px;padding:.3rem"
                        value="{{ old('theme.primary_color', $setting->theme['primary_color'] ?? '#4f46e5') }}"></div>
                <div><label class="label">Tema Sidebar</label>
                    <select name="theme[sidebar]" class="select">
                        <option value="dark" {{ old('theme.sidebar', $setting->theme['sidebar'] ?? 'dark') === 'dark' ? 'selected' : '' }}>Gelap</option>
                        <option value="light" {{ old('theme.sidebar', $setting->theme['sidebar'] ?? 'dark') === 'light' ? 'selected' : '' }}>Terang</option>
                    </select></div>
            </div>
            <div class="form-actions"><button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button></div>
        </form>
    </div>
</div>
@endsection