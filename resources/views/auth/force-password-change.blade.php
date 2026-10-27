@extends('layouts.guest')
@section('title', 'Ganti Password Sementara')
@section('subtitle', 'Untuk keamanan akun, buat password baru sebelum melanjutkan.')
@section('content')
<form method="POST" action="{{ route('password.force-change.update') }}" style="display:grid;gap:.9rem">
    @csrf
    @method('PUT')
    <div><label class="label">Password Baru</label>
        <input name="password" type="password" class="input" minlength="8" required autocomplete="new-password"></div>
    <div><label class="label">Konfirmasi Password Baru</label>
        <input name="password_confirmation" type="password" class="input" minlength="8" required autocomplete="new-password"></div>
    <button class="btn btn-primary" style="width:100%;padding:.7rem">Simpan Password Baru</button>
</form>
@endsection
