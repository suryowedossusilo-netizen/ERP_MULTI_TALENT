@extends('layouts.app')
@section('title', 'Buat Invoice')
@section('content')
<div class="card" style="max-width:640px">
    <div class="card-header">Buat Invoice dari Order</div>
    <div class="card-body">
        @if($orders->isEmpty())
            <p style="color:var(--muted)">Semua order sudah memiliki invoice.</p>
            <div class="form-actions"><a href="{{ route('sales.orders.index') }}" class="btn btn-secondary">Kembali</a></div>
        @else
        <form method="POST" action="{{ route('sales.invoices.store') }}">
            @csrf
            <div class="form-grid">
                <div style="grid-column:1/-1"><label class="label">Order *</label>
                    <select name="order_id" class="select" required>
                        <option value="">— Pilih Order (belum difaktur) —</option>
                        @foreach($orders as $o)
                            <option value="{{ $o->id }}">{{ $o->order_number }} — {{ $o->customer->name ?? 'Umum' }} ({{ rupiah($o->total) }})</option>
                        @endforeach
                    </select></div>
                <div><label class="label">Jatuh Tempo</label>
                    <input name="due_date" type="date" class="input" value="{{ now()->addDays(14)->toDateString() }}"></div>
            </div>
            <div class="form-actions">
                <a href="{{ route('sales.invoices.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-file-invoice"></i> Buat Invoice</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection