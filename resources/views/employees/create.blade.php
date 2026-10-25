@extends('layouts.app')
@section('title', 'Tambah Karyawan')
@section('content')
<div class="page-head"><div><h1 class="page-title">Tambah Karyawan</h1><p class="page-sub">Masukkan data karyawan baru</p></div></div>
<div class="card" style="max-width:760px"><div class="card-body">
<form method="POST" action="{{ route('employees.store') }}" style="display:grid;gap:1rem">@csrf
<div><label class="label">ID Karyawan *</label><input name="employee_code" class="input" value="{{ old('employee_code') }}" required maxlength="100"></div>
<div><label class="label">Nama Lengkap *</label><input name="name" class="input" value="{{ old('name') }}" required maxlength="255"></div>
<div><label class="label">Departemen</label><select name="department_id" class="input"><option value="">— Pilih —</option>@foreach($departments as $department)<option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>@endforeach</select></div>
<div><label class="label">Jabatan</label><select name="position_id" class="input"><option value="">— Pilih —</option>@foreach($positions as $position)<option value="{{ $position->id }}" @selected(old('position_id') == $position->id)>{{ $position->name }}</option>@endforeach</select></div>
<div><label class="label">Telepon</label><input name="phone" class="input" value="{{ old('phone') }}"></div>
<div><label class="label">Email</label><input name="email" type="email" class="input" value="{{ old('email') }}"><small class="text-muted">Wajib diisi jika akun login akan dibuat; email akun harus unik.</small></div>
<div><label class="label">Password Akun Karyawan (opsional)</label><input name="account_password" type="password" class="input" minlength="8" autocomplete="new-password"><small class="text-muted">Isi minimal 8 karakter untuk membuat akun login. Password tidak akan ditampilkan kembali.</small></div>
<div><label class="label">Tanggal Bergabung</label><input name="join_date" type="date" class="input" value="{{ old('join_date') }}"></div>
<div><label class="label">Gaji (opsional, sesuai izin)</label><input name="salary" type="number" min="0" step="0.01" class="input" value="{{ old('salary') }}"></div>
<div class="form-actions"><a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a><button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button></div>
</form></div></div>
@endsection
