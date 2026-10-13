<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin', 'tenant_id' => null]);
        $admin->permissions()->sync(Permission::pluck('id'));

        $staff = Role::firstOrCreate(['name' => 'staff', 'tenant_id' => null]);
        $staff->permissions()->sync(Permission::whereNotIn('name', ['manage-tenants', 'manage-users', 'manage-roles'])->pluck('id'));
    }
}
