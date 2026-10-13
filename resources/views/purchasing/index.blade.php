@extends('layouts.app')
@section('title', 'Purchase Order')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Purchase Order</h1><p class="page-sub">Pemesanan barang ke pemasok</p></div>
    <a href="{{ route('purchasing.purchase-orders.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Buat PO</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>No. PO</th><th>Pemasok</th><th>Tanggal</th><th>Status</th><th style="text-align:right">Total</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td><a href="{{ route('purchasing.purchase-orders.show', $item) }}" style="color:var(--primary);font-weight:600">{{ $item->po_number }}</a></td>
                    <td>{{ $item->supplier->name ?? '-' }}</td>
                    <td>{{ $item->order_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>@php $cls = ['received'=>'badge-success','cancelled'=>'badge-danger'][$item->status] ?? 'badge-warning'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($item->status) }}</span></td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($item->total) }}</td>
                    <td><div class="actions">
                        <a href="{{ route('purchasing.purchase-orders.show', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="{{ route('purchasing.purchase-orders.edit', $item) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                        <form method="POST" action="{{ route('purchasing.purchase-orders.destroy', $item) }}" onsubmit="return confirm('Hapus PO ini?')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Belum ada PO.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $items->links() }}</div>
</div>
@endsection