@extends('layouts.guest')
@section('title', 'Lupa Password')
@section('subtitle', 'Kami akan mengirimkan link reset ke email Anda.')
@section('content')
<form method="POST" action="{{ route('password.email') }}" style="display:grid;gap:.9rem">
    @csrf
    <div><label class="label">Email</label>
        <input name="email" type="email" class="input" placeholder="nama@perusahaan.com" required autofocus></div>
    <button class="btn btn-primary" style="width:100%;padding:.7rem"><i class="fa fa-paper-plane"></i> Kirim Link Reset</button>
</form>
<p style="text-align:center;font-size:.83rem;margin-top:1.1rem;color:var(--muted)">
    <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600">&larr; Kembali masuk</a>
</p>
@endsection