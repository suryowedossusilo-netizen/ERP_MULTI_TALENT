<?php
namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'demo'],
            ['name' => 'Demo Company', 'status' => 'active']
        );

        $admin = User::firstOrCreate(
            ['tenant_id' => $tenant->id, 'email' => 'admin@demo.test'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'is_active' => true]
        );

        $role = Role::firstOrCreate(
            ['tenant_id' => $tenant->id, 'name' => 'admin'],
            ['guard_name' => 'web']
        );

        $permissionIds = Permission::whereNotIn('name', ['manage-tenants'])->pluck('id');
        $role->permissions()->sync($permissionIds);
        $admin->roles()->syncWithoutDetaching([$role->id]);
    }
}
