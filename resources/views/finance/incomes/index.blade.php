{{-- finance/incomes/index.blade.php — pola tabel seperti biasa --}}
@extends('layouts.app')
@section('title', 'Pemasukan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Pemasukan</h1><p class="page-sub">Pencatatan pemasukan lain-lain</p></div>
    <a href="{{ route('finance.incomes.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>No.</th><th>Kategori</th><th>Tanggal</th><th>Deskripsi</th><th style="text-align:right">Jumlah</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td style="font-weight:600">{{ $item->income_number }}</td>
                    <td><span class="badge badge-success">{{ $item->category ?? '-' }}</span></td>
                    <td>{{ $item->income_date->format('d/m/Y') }}</td>
                    <td>{{ $item->description ?? '-' }}</td>
                    <td style="text-align:right" class="amount-in">+ {{ rupiah($item->amount) }}</td>
                    <td><div class="actions">
                        <a href="{{ route('finance.incomes.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                        <form method="POST" action="{{ route('finance.incomes.destroy', $item) }}" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection