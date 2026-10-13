@extends('layouts.app')
@section('title', 'Role')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Role</h1><p class="page-sub">Hak akses pengguna sistem</p></div>
    <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Role</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Nama</th><th style="text-align:right">Jml Pengguna</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td><a href="{{ route('roles.show', $item) }}" style="color:var(--primary);font-weight:600">{{ $item->name }}</a></td>
                    <td style="text-align:right"><span class="badge badge-info">{{ $item->users->count() }}</span></td>
                    <td><div class="actions">
                        <a href="{{ route('roles.show', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="{{ route('roles.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                        <form method="POST" action="{{ route('roles.destroy', $item) }}" onsubmit="return confirm('Hapus role ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="3" class="empty">Belum ada role.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection