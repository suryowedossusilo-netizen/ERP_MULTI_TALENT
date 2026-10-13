@extends('layouts.guest')
@section('title', 'Masuk')
@section('subtitle', 'Selamat datang kembali! Silakan masuk ke akun Anda.')
@section('content')
<form method="POST" action="{{ route('login') }}" style="display:grid;gap:.9rem">
    @csrf
    <div>
        <label class="label">Email</label>
        <input name="email" type="email" class="input" placeholder="nama@perusahaan.com" value="{{ old('email') }}" required autofocus>
    </div>
    <div>
        <label class="label">Password</label>
        <input name="password" type="password" class="input" placeholder="••••••••" required>
    </div>
    <button class="btn btn-primary" style="width:100%;padding:.7rem"><i class="fa fa-right-to-bracket"></i> Masuk</button>
</form>
<p style="text-align:center;font-size:.83rem;margin-top:1.1rem;color:var(--muted)">
    <a href="{{ route('register') }}" style="color:var(--primary);font-weight:600">Daftar tenant baru</a>
    &middot;
    <a href="{{ route('password.request') }}" style="color:var(--primary);font-weight:600">Lupa password?</a>
</p>
@endsection