@extends('layouts.app')
@section('title', 'Pelanggan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Pelanggan</h1><p class="page-sub">Kelola data pelanggan</p></div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Pelanggan</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Kode</th><th>Nama</th><th>Email</th><th>Telepon</th><th style="text-align:right">Limit Kredit</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td style="font-weight:600">{{ $item->code }}</td>
                    <td><a href="{{ route('customers.show', $item) }}" style="color:var(--primary);font-weight:600">{{ $item->name }}</a></td>
                    <td>{{ $item->email ?? '-' }}</td>
                    <td>{{ $item->phone ?? '-' }}</td>
                    <td style="text-align:right">{{ rupiah($item->credit_limit) }}</td>
                    <td><div class="actions">
                        <a href="{{ route('customers.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                        <form method="POST" action="{{ route('customers.destroy', $item) }}" onsubmit="return confirm('Hapus pelanggan ini?')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Belum ada pelanggan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection