@extends('layouts.app')
@section('title', 'Detail Karyawan')
@section('content')
<div class="page-head"><div><h1 class="page-title">Detail Karyawan</h1><p class="page-sub">Informasi karyawan</p></div><a href="{{ route('employees.edit', $item) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a></div>
<div class="card" style="max-width:760px"><div class="card-body">
<table class="erp-table"><tbody>
<tr><th>ID Karyawan</th><td>{{ $item->employee_code }}</td></tr><tr><th>Nama</th><td>{{ $item->name }}</td></tr>
<tr><th>Departemen</th><td>{{ $item->department?->name ?? '—' }}</td></tr><tr><th>Jabatan</th><td>{{ $item->position?->name ?? '—' }}</td></tr>
<tr><th>Telepon</th><td>{{ $item->phone ?? '—' }}</td></tr><tr><th>Email</th><td>{{ $item->email ?? '—' }}</td></tr>
<tr><th>Tanggal Bergabung</th><td>{{ $item->join_date?->format('d-m-Y') ?? '—' }}</td></tr>
@if(auth()->user()->hasPermission('manage-salaries') || auth()->user()->hasPermission('manage-payroll'))<tr><th>Gaji</th><td>{{ number_format((float) $item->salary, 2, ',', '.') }}</td></tr>@endif
<tr><th>Status</th><td>{{ ucfirst($item->status) }}</td></tr>
</tbody></table>
@if(auth()->user()->hasPermission('reset-employee-password') && $item->user_id)
<div class="card" style="margin-top:1rem"><div class="card-body">
<h2 class="page-title" style="font-size:1rem">Akun Login Karyawan</h2>
<p class="text-muted">Reset akan membuat password sementara dan mewajibkan karyawan menggantinya saat login berikutnya.</p>
<form method="POST" action="{{ route('employees.reset-password', $item) }}" onsubmit="return confirm('Reset password karyawan ini? Pastikan identitas karyawan sudah diverifikasi.')">@csrf<button class="btn btn-primary" type="submit">Reset Password Karyawan</button></form>
@if(session('temporary_password'))<div class="alert alert-success" style="margin-top:1rem"><strong>Password sementara (ditampilkan sekali):</strong><br><code>{{ session('temporary_password') }}</code><p>Berikan langsung kepada karyawan setelah verifikasi identitas. Jangan kirim melalui kanal publik.</p></div>@endif
</div></div>
@endif
<div class="form-actions" style="margin-top:1rem"><a href="{{ route('employees.index') }}" class="btn btn-secondary">Kembali</a></div>
</div></div>
@endsection
