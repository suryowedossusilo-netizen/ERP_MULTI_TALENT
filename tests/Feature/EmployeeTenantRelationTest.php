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

    public function test_user_without_salary_permission_cannot_view_or_change_salary(): void
    {
        $tenant = Tenant::create(['name' => 'Salary Company', 'slug' => 'salary-company', 'status' => 'active']);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Employee Admin',
            'email' => 'salary-admin@example.test',
            'password' => 'temporary-test-password',
            'is_active' => true,
        ]);

        $permission = Permission::firstOrCreate(
            ['name' => 'manage-employees', 'tenant_id' => null],
            ['guard_name' => 'web']
        );
        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'employee-admin-salary-test',
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $employee = \App\Models\Employee::create([
            'tenant_id' => $tenant->id,
            'employee_code' => 'SALARY-EMP-001',
            'name' => 'Private Salary Employee',
            'salary' => 1234.56,
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get('/employees/' . $employee->id . '/edit')
            ->assertOk()
            ->assertDontSee('1234.56')
            ->assertDontSee('name="salary"', false);

        $this->actingAs($user)->put('/employees/' . $employee->id, [
            'employee_code' => $employee->employee_code,
            'name' => $employee->name,
            'department_id' => null,
            'position_id' => null,
            'status' => 'active',
            'salary' => 9999.99,
        ])->assertRedirect('/employees');

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'salary' => 1234.56,
        ]);
    }

}
