@extends('layouts.app')
@section('title', 'Invoice')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Invoice</h1><p class="page-sub">Faktur tagihan pelanggan</p></div>
    <a href="{{ route('sales.invoices.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Buat Invoice</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>No. Invoice</th><th>Pelanggan</th><th>Jatuh Tempo</th><th>Status</th><th style="text-align:right">Tagihan</th><th style="text-align:right">Sisa</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($invoices as $inv)
                <tr>
                    <td><a href="{{ route('sales.invoices.show', $inv) }}" style="color:var(--primary);font-weight:600">{{ $inv->invoice_number }}</a></td>
                    <td>{{ $inv->customer->name ?? '-' }}</td>
                    <td>{{ $inv->due_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>@php $cls = ['paid'=>'badge-success','partial'=>'badge-warning'][$inv->status] ?? 'badge-danger'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($inv->status) }}</span></td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($inv->amount) }}</td>
                    <td style="text-align:right;color:var(--danger);font-weight:700">{{ rupiah($inv->outstanding) }}</td>
                    <td><div class="actions">
                        <a href="{{ route('sales.invoices.show', $inv) }}" class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></a>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">Belum ada invoice.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $invoices->links() }}</div>
</div>
@endsection