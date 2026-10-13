<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy
    |--------------------------------------------------------------------------
    */
    'tenant' => [
        'identification' => 'subdomain', // subdomain | domain | session
        'super_admin_emails' => [env('SUPER_ADMIN_EMAIL', 'admin@demo.test')],
    ],

    /*
    |--------------------------------------------------------------------------
    | Modul & penomoran default
    |--------------------------------------------------------------------------
    */
    'modules' => [
        'order'   => ['prefix' => 'SO',  'label' => 'Sales Order'],
        'invoice' => ['prefix' => 'INV', 'label' => 'Invoice'],
        'po'      => ['prefix' => 'PO',  'label' => 'Purchase Order'],
        'payment' => ['prefix' => 'PAY', 'label' => 'Pembayaran'],
        'income'  => ['prefix' => 'INC', 'label' => 'Pemasukan'],
        'expense' => ['prefix' => 'EXP', 'label' => 'Pengeluaran'],
    ],
];
