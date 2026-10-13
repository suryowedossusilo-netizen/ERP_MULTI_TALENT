@extends('layouts.app')
@section('title', 'PO ' . $item->po_number)
@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">PO {{ $item->po_number }}</h1>
        <p class="page-sub">{{ $item->supplier->name ?? '-' }} &middot; {{ $item->order_date?->format('d/m/Y') ?? '-' }}</p>
    </div>
    <a href="{{ route('purchasing.purchase-orders.edit', $item) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-icon" style="background:#e2e8f0;color:#475569"><i class="fa fa-flag"></i></div>
        <div><div class="stat-label">Status</div>
        <span class="badge {{ ['received'=>'badge-success','cancelled'=>'badge-danger'][$item->status] ?? 'badge-warning' }}">{{ ucfirst($item->status) }}</span></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--warning-bg);color:var(--warning)"><i class="fa fa-calculator"></i></div>
        <div><div class="stat-label">Subtotal</div><div class="stat-value" style="font-size:1.05rem">{{ rupiah($item->subtotal) }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--success-bg);color:var(--success)"><i class="fa fa-sack-dollar"></i></div>
        <div><div class="stat-label">Total</div><div class="stat-value" style="font-size:1.05rem;color:var(--primary)">{{ rupiah($item->total) }}</div></div></div>
</div>

<div class="card">
    <div class="card-header">Item Pembelian</div>
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Produk</th><th style="text-align:right">Qty</th><th style="text-align:right">Diterima</th><th style="text-align:right">Harga</th><th style="text-align:right">Subtotal</th></tr></thead>
            <tbody>
            @foreach($item->items as $it)
                <tr>
                    <td style="font-weight:600">{{ $it->product->name ?? '-' }}</td>
                    <td style="text-align:right">{{ $it->quantity }}</td>
                    <td style="text-align:right">{{ $it->received_quantity }}</td>
                    <td style="text-align:right">{{ rupiah($it->price) }}</td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($it->subtotal) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($item->status !== 'received' && $item->items->count())
@php $warehouses = \App\Models\Warehouse::where('tenant_id', tenant()->id)->get(); @endphp
<div class="card" style="margin-top:1rem">
    <div class="card-header"><i class="fa fa-truck-ramp-box"></i> Terima Barang</div>
    <div class="card-body">
        @if($warehouses->isEmpty())
            <p style="color:var(--muted)">Buat gudang terlebih dahulu sebelum menerima barang.</p>
        @else
        <form method="POST" action="{{ route('purchasing.receipts.store', $item) }}">
            @csrf
            <div style="margin-bottom:1rem">
                <label class="label">Gudang Tujuan *</label>
                <select name="warehouse_id" class="select" required>
                    @foreach($warehouses as $w)<option value="{{ $w->id }}">{{ $w->name }}</option>@endforeach
                </select>
            </div>
            <div class="table-wrap">
                <table class="erp-table">
                    <thead><tr><th>Produk</th><th style="text-align:right">Qty Order</th><th style="text-align:right">Sudah Terima</th><th style="width:150px">Qty Terima</th></tr></thead>
                    <tbody>
                    @foreach($item->items as $it)
                        <tr>
                            <td>{{ $it->product->name ?? '-' }}</td>
                            <td style="text-align:right">{{ $it->quantity }}</td>
                            <td style="text-align:right">{{ $it->received_quantity }}</td>
                            <td>
                                <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $it->id }}">
                                <input name="items[{{ $loop->index }}][qty]" type="number" step="0.001" min="0" max="{{ $it->quantity - $it->received_quantity }}"
                                    class="input" value="0">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary"><i class="fa fa-check"></i> Proses Penerimaan</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endif
@endsection