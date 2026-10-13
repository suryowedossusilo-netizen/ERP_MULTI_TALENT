@extends('layouts.app')
@section('title', 'Manajemen Tenant')
@section('content')
<div class="page-head">
    <div><h1 class="page-title">Tenant</h1><p class="page-sub">Seluruh perusahaan dalam sistem</p></div>
    <a href="{{ route('tenants.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Tenant</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table class="erp-table">
            <thead><tr><th>Nama</th><th>Domain</th><th>Email</th><th>Status</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
            @forelse($tenants as $t)
                <tr>
                    <td style="font-weight:600">{{ $t->name }}</td>
                    <td>{{ $t->domain ?? '-' }}</td>
                    <td>{{ $t->email ?? '-' }}</td>
                    <td>@php $cls = ['active'=>'badge-success','suspended'=>'badge-warning'][$t->status] ?? 'badge-danger'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($t->status) }}</span></td>
                    <td><div class="actions">
                        <a href="{{ route('tenants.edit', $t) }}" class="btn btn-secondary btn-sm"><i class="fa fa-pen"></i></a>
                        <form method="POST" action="{{ route('tenants.destroy', $t) }}" onsubmit="return confirm('Hapus tenant ini? Semua data ikut terhapus!')">
                            @csrf @method('DELETE')<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Belum ada tenant.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $tenants->links() }}</div>
</div>
@endsection