@extends('layouts.app')
@section('title', 'Jabatan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Jabatan</h1><p class="page-sub">Daftar posisi/jabatan karyawan</p></div>
    <a href="{{ route('positions.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Jabatan</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Nama</th><th style="text-align:right">Jml Karyawan</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td><a href="{{ route('positions.show', $item) }}" style="color:var(--primary);font-weight:600">{{ $item->name }}</a></td>
                    <td style="text-align:right"><span class="badge badge-info">{{ $item->employees->count() }}</span></td>
                    <td><div class="actions">
                        <a href="{{ route('positions.show', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="{{ route('positions.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                        <form method="POST" action="{{ route('positions.destroy', $item) }}" onsubmit="return confirm('Hapus jabatan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="3" class="empty">Belum ada jabatan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection