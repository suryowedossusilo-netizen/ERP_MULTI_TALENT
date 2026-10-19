@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('content')
<div class="page-head"><div><h1 class="page-title">Tambah Pengguna</h1><p class="page-sub">Buat akun untuk perusahaan ini.</p></div></div>
<div class="card" style="max-width:760px"><div class="card-body">
<form method="POST" action="{{ route('users.store') }}" style="display:grid;gap:1rem">
@csrf
<div><label class="label" for="name">Nama Lengkap *</label><input id="name" name="name" class="input" value="{{ old('name') }}" required maxlength="255" autocomplete="name"></div>
<div><label class="label" for="email">Email *</label><input id="email" name="email" type="email" class="input" value="{{ old('email') }}" required maxlength="255" autocomplete="email"></div>
<div><label class="label" for="password">Password *</label><input id="password" name="password" type="password" class="input" required minlength="8" autocomplete="new-password"><p class="page-sub">Minimal 8 karakter.</p></div>
<div class="form-actions"><a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Simpan Pengguna</button></div>
</form></div></div>
@endsection
