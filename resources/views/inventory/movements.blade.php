@extends('layouts.app')
@section('title', 'Pergerakan Stok')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Pergerakan Stok</h1><p class="page-sub">Riwayat masuk/keluar barang</p></div>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Tanggal</th><th>Produk</th><th>Gudang</th><th>Jenis</th><th style="text-align:right">Qty</th><th style="text-align:right">Sebelum</th><th style="text-align:right">Sesudah</th><th>Catatan</th></tr></thead>
            <tbody>
            @forelse($movements as $m)
                <tr>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-weight:600">{{ $m->product->name ?? '-' }}</td>
                    <td>{{ $m->warehouse->name ?? '-' }}</td>
                    <td><span class="badge {{ $m->type === 'in' ? 'badge-success' : 'badge-danger' }}">{{ $m->type === 'in' ? 'Masuk' : 'Keluar' }}</span></td>
                    <td style="text-align:right">{{ $m->quantity }}</td>
                    <td style="text-align:right">{{ $m->stock_before }}</td>
                    <td style="text-align:right;font-weight:700">{{ $m->stock_after }}</td>
                    <td style="color:var(--muted)">{{ $m->note ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty">Belum ada pergerakan stok.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $movements->links() }}</div>
</div>
@endsection