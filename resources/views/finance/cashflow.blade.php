@extends('layouts.app')
@section('title', 'Arus Kas')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Arus Kas</h1><p class="page-sub">Semua pemasukan & pengeluaran</p></div>
    <div style="display:flex;gap:.5rem">
        <a href="{{ route('finance.incomes.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Pemasukan</a>
        <a href="{{ route('finance.expenses.create') }}" class="btn btn-secondary"><i class="fa fa-minus"></i> Pengeluaran</a>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-icon" style="background:var(--success-bg);color:var(--success)"><i class="fa fa-arrow-trend-up"></i></div>
        <div><div class="stat-label">Total Masuk</div><div class="stat-value amount-in">+ {{ rupiah($totalIn) }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--danger-bg);color:var(--danger)"><i class="fa fa-arrow-trend-down"></i></div>
        <div><div class="stat-label">Total Keluar</div><div class="stat-value amount-out">− {{ rupiah($totalOut) }}</div></div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--primary-soft);color:var(--primary)"><i class="fa fa-scale-balanced"></i></div>
        <div><div class="stat-label">Saldo Bersih</div><div class="stat-value">{{ rupiah($totalIn - $totalOut) }}</div></div></div>
</div>

<div class="card">
    <div class="card-header">Transaksi</div>
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Tanggal</th><th>Kategori</th><th>Deskripsi</th><th style="text-align:right">Masuk</th><th style="text-align:right">Keluar</th></tr></thead>
            <tbody>
            @php
                $rows = $incomes->map(fn($i) => (object)['date' => $i->income_date, 'category' => $i->category, 'desc' => $i->description, 'in' => $i->amount, 'out' => null])
                    ->merge($expenses->map(fn($e) => (object)['date' => $e->expense_date, 'category' => $e->category, 'desc' => $e->description, 'in' => null, 'out' => $e->amount]))
                    ->sortBy('date');
            @endphp
            @forelse($rows as $r)
                <tr class="timeline-row {{ $r->in ? 'in' : 'out' }}">
                    <td>{{ $r->date->format('d/m/Y') }}</td>
                    <td><span class="badge {{ $r->in ? 'badge-success' : 'badge-danger' }}">{{ $r->category ?? '-' }}</span></td>
                    <td>{{ $r->desc ?? '-' }}</td>
                    <td style="text-align:right" class="amount-in">{{ $r->in ? '+ ' . rupiah($r->in) : '' }}</td>
                    <td style="text-align:right" class="amount-out">{{ $r->out ? '− ' . rupiah($r->out) : '' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection