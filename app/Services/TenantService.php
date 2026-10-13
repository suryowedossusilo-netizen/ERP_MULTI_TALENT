<?php
namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;

class TenantService
{
    public function createTenant(array $data): Tenant
    {
        $tenant = Tenant::create([
            'name'   => $data['company_name'],
            'slug'   => Str::slug($data['company_name']) . '-' . Str::random(6),
            'email'  => $data['email'] ?? null,
            'phone'  => $data['phone'] ?? null,
            'status' => 'active',
        ]);

        // Default numbering
        foreach (['order' => 'SO', 'invoice' => 'INV', 'po' => 'PO', 'payment' => 'PAY', 'income' => 'INC', 'expense' => 'EXP'] as $module => $prefix) {
            \App\Models\NumberingSetting::create([
                'tenant_id' => $tenant->id, 'module' => $module,
                'prefix' => $prefix, 'next_number' => 1, 'padding' => 5,
            ]);
        }
        return $tenant;
    }
}
