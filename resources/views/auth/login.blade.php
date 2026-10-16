@extends('layouts.guest')
@section('title', 'Masuk')
@section('subtitle', 'Masukkan ID perusahaan, email, dan password untuk masuk.')
@section('content')
<form method="POST" action="{{ route('login') }}" style="display:grid;gap:.9rem">
    @csrf
    <div>
        <label class="label">ID Perusahaan (slug)</label>
        <input name="company" class="input" placeholder="contoh: demo" value="{{ old('company') }}" required autofocus autocomplete="organization">
        <small style="color:var(--muted)">Gunakan slug perusahaan yang diberikan saat pendaftaran.</small>
    </div>
    <div>
        <label class="label">Email</label>
        <input name="email" type="email" class="input" placeholder="nama@perusahaan.com" value="{{ old('email') }}" required autocomplete="username">
    </div>
    <div>
        <label class="label">Password</label>
        <input name="password" type="password" class="input" placeholder="••••••••" required autocomplete="current-password">
    </div>
    <button class="btn btn-primary" style="width:100%;padding:.7rem"><i class="fa fa-right-to-bracket"></i> Masuk</button>
</form>
<p style="text-align:center;font-size:.83rem;margin-top:1.1rem;color:var(--muted)">
    <a href="{{ route('register') }}" style="color:var(--primary);font-weight:600">Daftar tenant baru</a>
    &middot;
    <a href="{{ route('password.request') }}" style="color:var(--primary);font-weight:600">Lupa password?</a>
</p>
@endsection