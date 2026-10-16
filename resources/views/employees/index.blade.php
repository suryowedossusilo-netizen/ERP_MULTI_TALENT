@extends('layouts.app')
@section('title', 'Karyawan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Karyawan</h1><p class="page-sub">Daftar karyawan perusahaan</p></div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Karyawan</a>
</div>
<div class="card"><div class="table-wrap"><table class="erp-table">
<thead><tr><th>ID Karyawan</th><th>Nama</th><th>Departemen</th><th>Jabatan</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($items as $item)
<tr>
<td>{{ $item->employee_code }}</td><td>{{ $item->name }}</td>
<td>{{ $item->department?->name ?? '—' }}</td><td>{{ $item->position?->name ?? '—' }}</td><td>{{ ucfirst($item->status) }}</td>
<td><div class="actions"><a class="btn btn-secondary btn-sm" href="{{ route('employees.show', $item) }}"><i class="fa fa-eye"></i></a><a class="btn btn-secondary btn-sm" href="{{ route('employees.edit', $item) }}"><i class="fa fa-pen"></i></a><form method="POST" action="{{ route('employees.destroy', $item) }}" onsubmit="return confirm('Hapus karyawan ini?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></form></div></td>
</tr>
@empty
<tr><td colspan="6" class="empty">Belum ada data karyawan.</td></tr>
@endforelse
</tbody></table></div><div class="pagination-wrapper">{{ $items->links() }}</div></div>
@endsection
