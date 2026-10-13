@extends('layouts.app')
@section('title', 'Detail Role')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">{{ $item->name }}</h1><p class="page-sub">Detail role & hak akses</p></div>
    <a href="{{ route('roles.edit', $item) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a>
</div>
<div class="card" style="max-width:480px">
    <div class="card-body">
        <div class="detail-row"><span class="k">Nama</span><span style="font-weight:700">{{ $item->name }}</span></div>
        <div class="detail-row"><span class="k">Jumlah Pengguna</span><span class="badge badge-info">{{ $item->users->count() }}</span></div>
        <div class="detail-row"><span class="k">Jumlah Permission</span><span class="badge badge-neutral">{{ $item->permissions->count() }}</span></div>
        <div class="detail-row" style="border:0"><span class="k">Dibuat</span><span>{{ $item->created_at->format('d/m/Y H:i') }}</span></div>
    </div>
</div>
@endsection