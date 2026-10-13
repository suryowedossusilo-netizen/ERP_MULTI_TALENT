@extends('layouts.app')
@section('title', 'Penomoran Dokumen')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header">Format Nomor Dokumen Otomatis</div>
    <div class="card-body">
        <form method="POST" action="{{ route('settings.numbering.update') }}">
            @csrf @method('PUT')
            <div class="table-wrap" style="margin-bottom:1rem">
                <table class="erp-table">
                    <thead><tr><th>Modul</th><th>Prefix</th><th>Nomor Berikutnya</th></tr></thead>
                    <tbody>
                    @foreach(['order'=>'Order','invoice'=>'Invoice','payment'=>'Pembayaran','purchase_order'=>'PO','income'=>'Pemasukan','expense'=>'Pengeluaran'] as $module => $label)
                        @php $s = $settings[$module] ?? null; @endphp
                        <tr>
                            <td style="font-weight:600">{{ $label }}</td>
                            <td><input name="modules[{{ $module }}][prefix]" class="input" value="{{ old("modules.$module.prefix", $s->prefix ?? strtoupper(substr($module,0,3))) }}"></td>
                            <td><input name="modules[{{ $module }}][next_number]" type="number" min="1" class="input" value="{{ old("modules.$module.next_number", $s->next_number ?? 1) }}"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="form-actions"><button class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Simpan</button></div>
        </form>
    </div>
</div>
@endsection