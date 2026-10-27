@extends('layouts.guest')
@section('title', 'Lupa Password')
@section('subtitle', 'Pemulihan email hanya berlaku untuk akun aktif di perusahaan yang dipilih. Karyawan tanpa email dapat meminta reset kepada admin perusahaan.')
@section('content')
<form method="POST" action="{{ route('password.email') }}" style="display:grid;gap:.9rem">
    @csrf
    <div><label class="label">ID Perusahaan (slug)</label>
        <input name="company" class="input" placeholder="contoh: demo" value="{{ old('company') }}" required autofocus autocomplete="organization"></div>
    <div><label class="label">Email</label>
        <input name="email" type="email" class="input" placeholder="nama@perusahaan.com" value="{{ old('email') }}" required autocomplete="username"></div>
    <button class="btn btn-primary" style="width:100%;padding:.7rem"><i class="fa fa-paper-plane"></i> Kirim Tautan Pemulihan</button>
</form>
<p style="text-align:center;font-size:.83rem;margin-top:1.1rem;color:var(--muted)">
    <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600">&larr; Kembali masuk</a>
</p>
@endsection
