@extends('layouts.app')
@section('title', 'Order Penjualan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Order Penjualan</h1><p class="page-sub">Kelola semua order</p></div>
    <a href="{{ route('sales.orders.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Order Baru</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>No. Order</th><th>Pelanggan</th><th>Tanggal</th><th>Status</th><th style="text-align:right">Total</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($orders as $o)
                <tr>
                    <td><a href="{{ route('sales.orders.show', $o) }}" style="color:var(--primary);font-weight:600">{{ $o->order_number }}</a></td>
                    <td>{{ $o->customer->name ?? '-' }}</td>
                    <td>{{ $o->created_at->format('d/m/Y') }}</td>
                    <td>@php $cls = ['completed'=>'badge-success','cancelled'=>'badge-danger'][$o->status] ?? 'badge-warning'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($o->status) }}</span></td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($o->total) }}</td>
                    <td><div class="actions">
                        <a href="{{ route('sales.orders.show', $o) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a>
                        <form method="POST" action="{{ route('sales.orders.destroy', $o) }}" onsubmit="return confirm('Hapus order ini?')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Belum ada order.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $orders->links() }}</div>
</div>
@endsection