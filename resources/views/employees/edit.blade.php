@extends('layouts.app')
@section('title', 'Edit Karyawan')
@section('content')
<div class="page-head"><div><h1 class="page-title">Edit Karyawan</h1><p class="page-sub">Perbarui data karyawan</p></div></div>
<div class="card" style="max-width:760px"><div class="card-body">
<form method="POST" action="{{ route('employees.update', $item) }}" style="display:grid;gap:1rem">@csrf @method('PUT')
<div><label class="label">ID Karyawan *</label><input name="employee_code" class="input" value="{{ old('employee_code', $item->employee_code) }}" required maxlength="100"></div>
<div><label class="label">Nama Lengkap *</label><input name="name" class="input" value="{{ old('name', $item->name) }}" required maxlength="255"></div>
<div><label class="label">Departemen</label><select name="department_id" class="input"><option value="">— Pilih —</option>@foreach($departments as $department)<option value="{{ $department->id }}" @selected(old('department_id', $item->department_id) == $department->id)>{{ $department->name }}</option>@endforeach</select></div>
<div><label class="label">Jabatan</label><select name="position_id" class="input"><option value="">— Pilih —</option>@foreach($positions as $position)<option value="{{ $position->id }}" @selected(old('position_id', $item->position_id) == $position->id)>{{ $position->name }}</option>@endforeach</select></div>
<div><label class="label">Telepon</label><input name="phone" class="input" value="{{ old('phone', $item->phone) }}"></div>
<div><label class="label">Email</label><input name="email" type="email" class="input" value="{{ old('email', $item->email) }}"></div>
<div><label class="label">Tanggal Bergabung</label><input name="join_date" type="date" class="input" value="{{ old('join_date', optional($item->join_date)->format('Y-m-d')) }}"></div>
@if(auth()->user()->hasPermission('manage-salaries') || auth()->user()->hasPermission('manage-payroll'))
<div><label class="label">Gaji</label><input name="salary" type="number" min="0" step="0.01" class="input" value="{{ old('salary', $item->salary) }}"></div>
@endif
<div><label class="label">Status</label><select name="status" class="input" required><option value="active" @selected(old('status', $item->status) === 'active')>Aktif</option><option value="resigned" @selected(old('status', $item->status) === 'resigned')>Mengundurkan diri</option><option value="terminated" @selected(old('status', $item->status) === 'terminated')>Diberhentikan</option></select></div>
<div class="form-actions"><a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a><button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button></div>
</form></div></div>
@endsection
