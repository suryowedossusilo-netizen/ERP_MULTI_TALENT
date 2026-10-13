@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Pembayaran</h1><p class="page-sub">Riwayat penerimaan pembayaran invoice</p></div>
    <a href="{{ route('sales.payments.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Catat Pembayaran</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>No. Pembayaran</th><th>Invoice</th><th>Tanggal</th><th>Metode</th><th style="text-align:right">Jumlah</th></tr></thead>
            <tbody>
            @forelse($payments as $p)
                <tr>
                    <td style="font-weight:600">{{ $p->payment_number }}</td>
                    <td><a href="{{ route('sales.invoices.show', $p->invoice) }}" style="color:var(--primary)">{{ $p->invoice->invoice_number ?? '-' }}</a></td>
                    <td>{{ $p->payment_date->format('d/m/Y') }}</td>
                    <td><span class="badge badge-info">{{ $p->method }}</span></td>
                    <td style="text-align:right;font-weight:700" class="amount-in">+ {{ rupiah($p->amount) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Belum ada pembayaran.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $payments->links() }}</div>
</div>
@endsection