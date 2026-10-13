@extends('layouts.app')
@section('title', 'Order Baru')
@section('content')
<div class="card">
    <div class="card-header">Buat Order Penjualan Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('sales.orders.store') }}" id="orderForm">
            @csrf
            <div class="form-grid" style="margin-bottom:1.25rem">
                <div><label class="label">Pelanggan</label>
                    <select name="customer_id" class="select">
                        <option value="">— Umum / Walk-in —</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->code }} — {{ $c->name }}</option>
                        @endforeach
                    </select></div>
                <div style="grid-column:2/-1"><label class="label">Catatan</label>
                    <input name="note" class="input" placeholder="Opsional"></div>
            </div>

            <label class="label">Item Order *</label>
            <div class="table-wrap" style="margin-bottom:.75rem">
                <table class="erp-table" id="itemsTable">
                    <thead><tr><th style="width:35%">Produk</th><th style="width:12%">Qty</th><th style="width:18%">Harga</th><th style="width:18%">Subtotal</th><th style="width:8%"></th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addRow()"><i class="fa fa-plus"></i> Tambah Item</button>

            <div style="display:grid;grid-template-columns:1fr 260px;gap:2rem;margin-top:1.5rem;align-items:start">
                <div class="form-grid">
                    <div><label class="label">Diskon (Rp)</label>
                        <input name="discount" id="discount" type="number" step="0.01" min="0" class="input" value="0" oninput="recalc()"></div>
                    <div><label class="label">Pajak (Rp)</label>
                        <input name="tax" id="tax" type="number" step="0.01" min="0" class="input" value="0" oninput="recalc()"></div>
                </div>
                <div class="card" style="background:var(--bg)">
                    <div class="card-body" style="padding:1rem">
                        <div class="detail-row"><span class="k">Subtotal</span><span id="vSub" style="font-weight:700">Rp 0</span></div>
                        <div class="detail-row"><span class="k">Diskon</span><span id="vDisc">Rp 0</span></div>
                        <div class="detail-row"><span class="k">Pajak</span><span id="vTax">Rp 0</span></div>
                        <div class="detail-row" style="border:0;font-size:1.1rem"><span class="k" style="font-weight:800;color:var(--text)">TOTAL</span><span id="vTotal" style="font-weight:800;color:var(--primary)">Rp 0</span></div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('sales.orders.index') }}" class="btn btn-secondary">Batal</a>
                <button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan Order</button>
            </div>
        </form>
    </div>
</div>

<template id="rowTpl">
<tr>
    <td>
        <select name="items[IDX][product_id]" class="select" required onchange="pickPrice(this)">
            <option value="">— Pilih Produk —</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}">{{ $p->sku }} — {{ $p->name }}</option>
            @endforeach
        </select>
    </td>
    <td><input name="items[IDX][quantity]" type="number" step="0.001" min="0.001" class="input" required oninput="recalc()"></td>
    <td><input name="items[IDX][price]" type="number" step="0.01" min="0" class="input" required oninput="recalc()"></td>
    <td class="rowSub" style="font-weight:700;vertical-align:middle">Rp 0</td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();recalc()"><i class="fa fa-trash"></i></button></td>
</tr>
</template>

<script>
let idx = 0;
const fmt = n => 'Rp ' + (Math.round(n) || 0).toLocaleString('id-ID');
function addRow(){
    const tbody = document.querySelector('#itemsTable tbody');
    tbody.insertAdjacentHTML('beforeend', document.getElementById('rowTpl').innerHTML.replaceAll('IDX', idx++));
}
function pickPrice(sel){
    const price = sel.selectedOptions[0]?.dataset.price || 0;
    const tr = sel.closest('tr');
    tr.querySelector('[name$="[price]"]').value = price;
    recalc();
}
function recalc(){
    let sub = 0;
    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        const q = parseFloat(tr.querySelector('[name$="[quantity]"]').value) || 0;
        const p = parseFloat(tr.querySelector('[name$="[price]"]').value) || 0;
        tr.querySelector('.rowSub').textContent = fmt(q * p);
        sub += q * p;
    });
    const d = parseFloat(document.getElementById('discount').value) || 0;
    const t = parseFloat(document.getElementById('tax').value) || 0;
    document.getElementById('vSub').textContent = fmt(sub);
    document.getElementById('vDisc').textContent = fmt(d);
    document.getElementById('vTax').textContent = fmt(t);
    document.getElementById('vTotal').textContent = fmt(sub - d + t);
}
addRow();
</script>
@endsection