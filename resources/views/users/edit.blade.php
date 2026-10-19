@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('content')
<div class="page-head"><div><h1 class="page-title">Edit Pengguna</h1><p class="page-sub">Perbarui informasi akun.</p></div></div>
<div class="card" style="max-width:760px"><div class="card-body">
<form method="POST" action="{{ route('users.update', $item) }}" style="display:grid;gap:1rem">
@csrf @method('PUT')
<div><label class="label" for="name">Nama Lengkap *</label><input id="name" name="name" class="input" value="{{ old('name', $item->name) }}" required maxlength="255" autocomplete="name"></div>
<div><label class="label" for="email">Email *</label><input id="email" name="email" type="email" class="input" value="{{ old('email', $item->email) }}" required maxlength="255" autocomplete="email"></div>
<div><label class="label" for="password">Password Baru</label><input id="password" name="password" type="password" class="input" minlength="8" autocomplete="new-password"><p class="page-sub">Kosongkan jika tidak ingin mengubah password.</p></div>
<div class="form-actions"><a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
</form></div></div>
@endsection
