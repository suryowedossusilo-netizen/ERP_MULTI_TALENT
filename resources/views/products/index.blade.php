@extends('layouts.app')
@section('title', 'Produk')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Produk</h1><p class="page-sub">Kelola katalog produk Anda</p></div>
    <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Produk</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>SKU</th><th>Barcode</th><th>Nama</th><th>Kategori</th><th style="text-align:right">Harga Jual</th><th class="text-right" style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td style="font-weight:600">{{ $item->sku }}</td>
                    <td>{{ $item->barcode ?? '-' }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($item->selling_price) }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('products.show', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('products.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                            <form method="POST" action="{{ route('products.destroy', $item) }}" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty"><i class="fa fa-box-open" style="font-size:1.5rem;display:block;margin-bottom:.5rem"></i>Belum ada produk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection