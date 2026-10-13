@if(session('success'))
    <div class="alert alert-success"><i class="fa fa-circle-check"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="fa fa-circle-exclamation"></i> {{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <i class="fa fa-triangle-exclamation"></i> Periksa kembali isian Anda:
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif