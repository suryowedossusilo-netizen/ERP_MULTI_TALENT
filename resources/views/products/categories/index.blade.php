@extends('layouts.app')
@section('title', 'Kategori')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Kategori</h1><p class="page-sub">Kelompokkan produk Anda</p></div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kategori</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Nama</th><th style="text-align:right">Jml Produk</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td><a href="{{ route('categories.show', $item) }}" style="color:var(--primary);font-weight:600">{{ $item->name }}</a></td>
                    <td style="text-align:right"><span class="badge badge-info">{{ $item->products->count() }}</span></td>
                    <td><div class="actions">
                        <a href="{{ route('categories.show', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="{{ route('categories.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                        <form method="POST" action="{{ route('categories.destroy', $item) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="3" class="empty">Belum ada kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection