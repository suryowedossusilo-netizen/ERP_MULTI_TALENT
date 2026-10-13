@extends('layouts.app')
@section('title', 'Detail Pelanggan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">{{ $item->name }}</h1><p class="page-sub">Kode: {{ $item->code }}</p></div>
    <a href="{{ route('customers.edit', $item) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a>
</div>
<div class="card" style="max-width:640px"><div class="card-body">
    <div class="detail-row"><span class="k">Email</span><span>{{ $item->email ?? '-' }}</span></div>
    <div class="detail-row"><span class="k">Telepon</span><span>{{ $item->phone ?? '-' }}</span></div>
    <div class="detail-row"><span class="k">Alamat</span><span>{{ $item->address ?? '-' }}</span></div>
    <div class="detail-row"><span class="k">Limit Kredit</span><span>{{ rupiah($item->credit_limit) }}</span></div>
    <div class="detail-row"><span class="k">Total Order</span><span class="badge badge-info">{{ $item->orders->count() }}</span></div>
</div></div>
@endsection