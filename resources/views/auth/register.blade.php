@extends('layouts.guest')
@section('title', 'Daftar Tenant Baru')
@section('subtitle', 'Mulai kelola bisnis Anda dalam satu platform.')
@section('content')
<form method="POST" action="{{ route('register') }}" style="display:grid;gap:.9rem">
    @csrf
    <div><label class="label">Nama Perusahaan</label>
        <input name="company_name" class="input" placeholder="PT Contoh Sejahtera" value="{{ old('company_name') }}" required></div>
    <div><label class="label">Nama Admin</label>
        <input name="name" class="input" placeholder="Nama lengkap" value="{{ old('name') }}" required></div>
    <div><label class="label">Email</label>
        <input name="email" type="email" class="input" placeholder="nama@perusahaan.com" value="{{ old('email') }}" required></div>
    <div><label class="label">Password</label>
        <input name="password" type="password" class="input" placeholder="Min. 8 karakter" required></div>
    <div><label class="label">Konfirmasi Password</label>
        <input name="password_confirmation" type="password" class="input" placeholder="Ulangi password" required></div>
    <button class="btn btn-primary" style="width:100%;padding:.7rem"><i class="fa fa-user-plus"></i> Daftar Sekarang</button>
</form>
<p style="text-align:center;font-size:.83rem;margin-top:1.1rem;color:var(--muted)">
    Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600">Masuk</a>
</p>
@endsection