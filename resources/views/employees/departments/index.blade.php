@extends('layouts.app')
@section('title', 'Departemen')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Departemen</h1><p class="page-sub">Daftar departemen perusahaan</p></div>
    <a href="{{ route('departments.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Departemen</a>
</div>
<div class="card"><div class="table-wrap"><table class="erp-table">
<thead><tr><th>Nama Departemen</th><th style="text-align:right">Jumlah Karyawan</th><th style="text-align:right">Aksi</th></tr></thead>
<tbody>
@forelse($items as $item)
<tr><td><a href="{{ route('departments.show', $item) }}" style="color:var(--primary);font-weight:600">{{ $item->name }}</a></td>
<td style="text-align:right">{{ $item->employees()->count() }}</td>
<td><div class="actions"><a href="{{ route('departments.show', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a><a href="{{ route('departments.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a><form method="POST" action="{{ route('departments.destroy', $item) }}" onsubmit="return confirm('Hapus departemen ini?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></form></div></td></tr>
@empty
<tr><td colspan="3" class="empty">Belum ada departemen.</td></tr>
@endforelse
</tbody></table></div><div class="pagination-wrapper">{{ $items->links() }}</div></div>
@endsection
