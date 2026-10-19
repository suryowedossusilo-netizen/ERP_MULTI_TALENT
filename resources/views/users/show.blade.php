@extends('layouts.app')
@section('title', 'Detail Pengguna')
@section('content')
<div class="page-head"><div><h1 class="page-title">Detail Pengguna</h1><p class="page-sub">Informasi akun pengguna.</p></div><a href="{{ route('users.edit', $item) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit Pengguna</a></div>
<div class="card" style="max-width:760px"><div class="card-body">
<table class="erp-table"><tbody>
<tr><th>Nama</th><td>{{ $item->name }}</td></tr>
<tr><th>Email</th><td>{{ $item->email }}</td></tr>
<tr><th>Status</th><td>{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
<tr><th>Role</th><td>{{ $item->roles->pluck('name')->join(', ') ?: 'Belum ada role' }}</td></tr>
<tr><th>Dibuat</th><td>{{ $item->created_at?->format('d-m-Y H:i') ?? '—' }}</td></tr>
</tbody></table>
<div class="form-actions" style="margin-top:1rem"><a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a></div>
</div></div>
@endsection
