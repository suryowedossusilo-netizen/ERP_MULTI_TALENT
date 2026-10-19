<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTenantRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_cannot_be_created_with_another_tenants_department(): void
    {
        $tenantA = Tenant::create(['name' => 'Company A', 'slug' => 'employee-a', 'status' => 'active']);
        $tenantB = Tenant::create(['name' => 'Company B', 'slug' => 'employee-b', 'status' => 'active']);

        $user = User::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Company A Admin',
            'email' => 'employee-admin@example.test',
            'password' => 'temporary-test-password',
            'is_active' => true,
        ]);

        $permission = Permission::firstOrCreate(
            ['name' => 'manage-employees', 'tenant_id' => null],
            ['guard_name' => 'web']
        );
        $role = Role::create([
            'tenant_id' => $tenantA->id,
            'name' => 'employee-admin-test',
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $foreignDepartment = Department::create([
            'tenant_id' => $tenantB->id,
            'name' => 'Company B Private Department',
        ]);

        $this->actingAs($user)->post('/employees', [
            'employee_code' => 'EMP-A-001',
            'name' => 'Test Employee',
            'department_id' => $foreignDepartment->id,
            'position_id' => null,
        ])->assertStatus(422);

        $this->assertDatabaseMissing('employees', [
            'tenant_id' => $tenantA->id,
            'employee_code' => 'EMP-A-001',
        ]);
    }
}
