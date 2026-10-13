@extends('layouts.app')
@section('title', 'Laporan Keuangan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Laporan Keuangan</h1><p class="page-sub">Pemasukan vs pengeluaran per bulan</p></div>
</div>
@php
    $months = collect($byMonthIncome->keys())->merge($byMonthExpense->keys())->unique()->sort();
    $maxVal = max(1, $months->map(fn($m) => ($byMonthIncome[$m] ?? 0) + ($byMonthExpense[$m] ?? 0))->max() ?? 1);
@endphp
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Bulan</th><th style="width:35%">Pemasukan</th><th style="width:35%">Pengeluaran</th><th style="text-align:right">Laba Bersih</th></tr></thead>
            <tbody>
            @forelse($months as $m)
                @php $inc = $byMonthIncome[$m] ?? 0; $exp = $byMonthExpense[$m] ?? 0; @endphp
                <tr>
                    <td style="font-weight:600">{{ $m }}</td>
                    <td>
                        <div style="background:var(--success-bg);border-radius:.4rem;height:20px;width:{{ max(2, $inc / $maxVal * 100) }}%;min-width:2px"></div>
                        <small class="amount-in">{{ rupiah($inc) }}</small>
                    </td>
                    <td>
                        <div style="background:var(--danger-bg);border-radius:.4rem;height:20px;width:{{ max(2, $exp / $maxVal * 100) }}%;min-width:2px"></div>
                        <small class="amount-out">{{ rupiah($exp) }}</small>
                    </td>
                    <td style="text-align:right;font-weight:700">{{ rupiah($inc - $exp) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection