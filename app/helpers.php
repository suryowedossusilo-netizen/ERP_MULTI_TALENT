<?php

if (!function_exists('tenant')) {
    function tenant(): ?\App\Models\Tenant
    {
        $container = app();

        return $container->bound('tenant') ? $container->make('tenant') : null;
    }
}

if (!function_exists('rupiah')) {
    function rupiah(float $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
