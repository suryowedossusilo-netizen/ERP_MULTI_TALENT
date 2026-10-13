@extends('layouts.app')
@section('title', 'Satuan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Satuan</h1><p class="page-sub">Unit of measure produk (pcs, kg, liter, dsb)</p></div>
    <a href="{{ route('units.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Satuan</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Nama</th><th>Singkatan</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td><a href="{{ route('units.show', $item) }}" style="color:var(--primary);font-weight:600">{{ $item->name }}</a></td>
                    <td><span class="badge badge-neutral">{{ $item->abbreviation ?? '-' }}</span></td>
                    <td><div class="actions">
                        <a href="{{ route('units.show', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="{{ route('units.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                        <form method="POST" action="{{ route('units.destroy', $item) }}" onsubmit="return confirm('Hapus satuan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="3" class="empty">Belum ada satuan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection 