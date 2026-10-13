<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="guest-body">
    <div class="guest-card">
        <div class="guest-logo"><i class="fa fa-cubes"></i></div>
        <h1 class="guest-title">@yield('title')</h1>
        <p class="guest-sub">@yield('subtitle')</p>
        @include('layouts.alerts')
        @yield('content')
    </div>
</body>
</html>