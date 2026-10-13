@extends('layouts.app')
@section('title', 'Catat Pembayaran')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header">Catat Pembayaran Invoice</div>
    <div class="card-body">
        @if($invoices->isEmpty())
            <p style="color:var(--muted)">Tidak ada invoice yang belum lunas.</p>
        @else
        <form method="POST" action="{{ route('sales.payments.store') }}">
            @csrf
            <div class="form-grid">
                <div style="grid-column:1/-1"><label class="label">Invoice *</label>
                    <select name="invoice_id" class="select" required>
                        <option value="">— Pilih Invoice —</option>
                        @foreach($invoices as $inv)
                            <option value="{{ $inv->id }}">{{ $inv->invoice_number }} — sisa {{ rupiah($inv->outstanding) }}</option>
                        @endforeach
                    </select></div>
                <div><label class="label">Jumlah (Rp) *</label>
                    <input name="amount" type="number" step="0.01" min="1" class="input" required></div>
                <div><label class="label">Metode *</label>
                    <select name="method" class="select" required>
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="card">Kartu</option>
                        <option value="other">Lainnya</option>
                    </select></div>
                <div><label class="label">Tanggal *</label>
                    <input name="payment_date" type="date" class="input" value="{{ now()->toDateString() }}" required></div>
                <div style="grid-column:1/-1"><label class="label">Catatan</label>
                    <input name="note" class="input"></div>
            </div>
            <div class="form-actions">
                <a href="{{ route('sales.payments.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection