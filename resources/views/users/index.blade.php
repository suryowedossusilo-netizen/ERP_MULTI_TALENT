@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Pengguna</h1>
        <p class="page-sub">Kelola akun pengguna perusahaan ini.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Pengguna
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td style="font-weight:600">{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('users.show', $item) }}" class="btn btn-secondary btn-sm" aria-label="Lihat pengguna">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $item) }}" class="btn btn-secondary btn-sm" aria-label="Edit pengguna">
                                    <i class="fa fa-pen"></i>
                                </a>
                                @if ((int) $item->id !== (int) auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $item) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" aria-label="Hapus pengguna">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">Belum ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection
