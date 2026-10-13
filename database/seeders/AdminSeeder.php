<?php
namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
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
            ['email' => 'admin@demo.test'],
            ['tenant_id' => $tenant->id, 'name' => 'Administrator', 'password' => Hash::make('password')]
        );

        $admin->roles()->sync(\App\Models\Role::where('name', 'admin')->pluck('id'));
    }
}
