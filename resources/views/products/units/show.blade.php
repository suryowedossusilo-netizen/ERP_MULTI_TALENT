@extends('layouts.app')
@section('title', 'Detail Satuan')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">{{ $item->name }}</h1><p class="page-sub">Detail satuan</p></div>
    <a href="{{ route('units.edit', $item) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a>
</div>
<div class="card" style="max-width:480px">
    <div class="card-body">
        <div class="detail-row"><span class="k">Nama</span><span style="font-weight:700">{{ $item->name }}</span></div>
        <div class="detail-row"><span class="k">Singkatan</span><span class="badge badge-neutral">{{ $item->abbreviation ?? '-' }}</span></div>
        <div class="detail-row" style="border:0"><span class="k">Dibuat</span><span>{{ $item->created_at->format('d/m/Y H:i') }}</span></div>
    </div>
</div>
@endsection