@extends('layouts.guest')
@section('title', 'Atur Password Baru')
@section('subtitle', 'Konfirmasi identitas perusahaan dan buat password baru.')
@section('content')
<form method="POST" action="{{ route('password.update') }}" style="display:grid;gap:.9rem">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div><label class="label">ID Perusahaan (slug)</label>
        <input name="company" class="input" value="{{ old('company', $company) }}" placeholder="contoh: demo" required autocomplete="organization"></div>
    <div><label class="label">Email</label>
        <input name="email" type="email" class="input" value="{{ old('email', $email) }}" required autocomplete="username"></div>
    <div><label class="label">Password Baru</label>
        <input name="password" type="password" class="input" minlength="8" required autocomplete="new-password"></div>
    <div><label class="label">Konfirmasi Password</label>
        <input name="password_confirmation" type="password" class="input" minlength="8" required autocomplete="new-password"></div>
    <button class="btn btn-primary" style="width:100%;padding:.7rem"><i class="fa fa-key"></i> Simpan Password Baru</button>
</form>
@endsection
