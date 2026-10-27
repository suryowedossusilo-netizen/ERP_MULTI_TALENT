<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Company administrator roles are created per tenant by registration/AdminSeeder.
        // Never attach manage-tenants to a shared role that could be assigned to a tenant user.
        $staff = Role::firstOrCreate(
            ['name' => 'staff', 'tenant_id' => null],
            ['guard_name' => 'web']
        );
        $staff->permissions()->sync(
            Permission::whereNotIn('name', [
                'manage-tenants', 'manage-users', 'manage-roles',
                'manage-salaries', 'manage-payroll', 'reset-employee-password',
            ])->pluck('id')
        );
    }
}
