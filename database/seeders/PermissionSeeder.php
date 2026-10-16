<?php
namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage-tenants', 'manage-users', 'manage-roles',
            'manage-products', 'manage-inventory', 'manage-sales',
            'manage-purchasing', 'manage-finance', 'view-reports',
            'manage-settings', 'manage-employees', 'manage-salaries', 'manage-payroll',
        ];
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'tenant_id' => null]);
        }
    }
}
