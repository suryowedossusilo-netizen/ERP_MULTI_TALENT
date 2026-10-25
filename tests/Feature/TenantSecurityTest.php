<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function tenant(string $slug, string $status = 'active'): Tenant
    {
        return Tenant::create(['name' => ucfirst($slug), 'slug' => $slug, 'status' => $status]);
    }

    private function user(Tenant $tenant, string $email): User
    {
        return User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test User',
            'email' => $email,
            'password' => 'test-password-123',
            'is_active' => true,
        ]);
    }

    private function grant(User $user, string $permissionName): void
    {
        $permission = Permission::firstOrCreate(
            ['name' => $permissionName, 'tenant_id' => null],
            ['guard_name' => 'web']
        );
        $role = Role::create([
            'tenant_id' => $user->tenant_id,
            'name' => 'test-' . $permissionName,
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);
    }

    public function test_tenant_admin_cannot_open_another_tenants_user_edit_page(): void
    {
        $tenantA = $this->tenant('company-a');
        $tenantB = $this->tenant('company-b');
        $adminA = $this->user($tenantA, 'admin-a@example.test');
        $userB = $this->user($tenantB, 'user-b@example.test');
        $this->grant($adminA, 'manage-users');

        $this->actingAs($adminA)->get('/users/' . $userB->id . '/edit')->assertNotFound();
        $this->assertDatabaseHas('users', ['id' => $userB->id, 'tenant_id' => $tenantB->id]);
    }

    public function test_tenant_admin_cannot_delete_another_tenants_user(): void
    {
        $tenantA = $this->tenant('delete-a');
        $tenantB = $this->tenant('delete-b');
        $adminA = $this->user($tenantA, 'delete-admin@example.test');
        $userB = $this->user($tenantB, 'delete-target@example.test');
        $this->grant($adminA, 'manage-users');

        $this->actingAs($adminA)->delete('/users/' . $userB->id)->assertNotFound();
        $this->assertDatabaseHas('users', ['id' => $userB->id]);
    }

    public function test_user_with_required_permission_can_access_user_management(): void
    {
        $tenant = $this->tenant('permission-granted');
        $admin = $this->user($tenant, 'permission-granted@example.test');
        $this->grant($admin, 'manage-users');

        $this->actingAs($admin)->get('/users')->assertOk();
    }

    public function test_user_without_required_permission_is_forbidden_from_user_management(): void
    {
        $tenant = $this->tenant('no-permission');
        $user = $this->user($tenant, 'no-permission@example.test');

        $this->actingAs($user)->get('/users')->assertForbidden();
    }

    public function test_tenant_admin_cannot_open_another_tenants_role(): void
    {
        $tenantA = $this->tenant('role-a');
        $tenantB = $this->tenant('role-b');
        $adminA = $this->user($tenantA, 'role-admin@example.test');
        $roleB = Role::create([
            'tenant_id' => $tenantB->id,
            'name' => 'private-role',
            'guard_name' => 'web',
        ]);
        $this->grant($adminA, 'manage-roles');

        $this->actingAs($adminA)->get('/roles/' . $roleB->id)->assertNotFound();
        $this->assertDatabaseHas('roles', ['id' => $roleB->id, 'tenant_id' => $tenantB->id]);
    }

    public function test_stock_adjustment_rejects_product_from_another_tenant(): void
    {
        $tenantA = $this->tenant('stock-a');
        $tenantB = $this->tenant('stock-b');
        $adminA = $this->user($tenantA, 'stock-admin@example.test');
        $this->grant($adminA, 'manage-inventory');

        $foreignProduct = Product::create([
            'tenant_id' => $tenantB->id,
            'sku' => 'FOREIGN-SKU-01',
            'name' => 'Foreign Product',
            'purchase_price' => 1,
            'selling_price' => 2,
            'min_stock' => 0,
            'is_active' => true,
        ]);
        $warehouse = Warehouse::create([
            'tenant_id' => $tenantA->id,
            'code' => 'WAREHOUSE-A-01',
            'name' => 'Tenant A Warehouse',
        ]);

        $this->actingAs($adminA)->post('/inventory/adjust', [
            'product_id' => $foreignProduct->id,
            'warehouse_id' => $warehouse->id,
            'type' => 'in',
            'quantity' => 1,
        ])->assertSessionHasErrors('product_id');

        $this->assertDatabaseMissing('stocks', [
            'tenant_id' => $tenantA->id,
            'product_id' => $foreignProduct->id,
            'warehouse_id' => $warehouse->id,
        ]);
        $this->assertDatabaseMissing('stock_movements', [
            'tenant_id' => $tenantA->id,
            'product_id' => $foreignProduct->id,
            'warehouse_id' => $warehouse->id,
        ]);
    }

    public function test_stock_adjustment_rejects_warehouse_from_another_tenant(): void
    {
        $tenantA = $this->tenant('warehouse-a');
        $tenantB = $this->tenant('warehouse-b');
        $adminA = $this->user($tenantA, 'warehouse-admin@example.test');
        $this->grant($adminA, 'manage-inventory');

        $product = Product::create([
            'tenant_id' => $tenantA->id,
            'sku' => 'LOCAL-SKU-01',
            'name' => 'Local Product',
            'purchase_price' => 1,
            'selling_price' => 2,
            'min_stock' => 0,
            'is_active' => true,
        ]);
        $foreignWarehouse = Warehouse::create([
            'tenant_id' => $tenantB->id,
            'code' => 'WAREHOUSE-B-01',
            'name' => 'Tenant B Warehouse',
        ]);

        $this->actingAs($adminA)->post('/inventory/adjust', [
            'product_id' => $product->id,
            'warehouse_id' => $foreignWarehouse->id,
            'type' => 'in',
            'quantity' => 1,
        ])->assertSessionHasErrors('warehouse_id');

        $this->assertDatabaseMissing('stocks', [
            'tenant_id' => $tenantA->id,
            'product_id' => $product->id,
            'warehouse_id' => $foreignWarehouse->id,
        ]);
    }

    public function test_permission_from_another_tenant_does_not_grant_access(): void
    {
        $tenantA = $this->tenant('permission-a');
        $tenantB = $this->tenant('permission-b');
        $userA = $this->user($tenantA, 'permission-a@example.test');

        $foreignPermission = Permission::create([
            'tenant_id' => $tenantB->id,
            'name' => 'manage-users',
            'guard_name' => 'web',
        ]);
        $roleA = Role::create([
            'tenant_id' => $tenantA->id,
            'name' => 'foreign-permission-test',
            'guard_name' => 'web',
        ]);
        $roleA->permissions()->attach($foreignPermission->id);
        $userA->roles()->attach($roleA->id);

        $this->actingAs($userA)->get('/users')->assertForbidden();
    }

    public function test_active_tenant_employee_can_log_in_with_employee_identity(): void
    {
        $tenant = $this->tenant('login-company');
        $user = $this->user($tenant, 'login@example.test');
        Employee::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'employee_code' => 'EMP-LOGIN-01',
            'name' => 'Login Employee',
            'status' => 'active',
        ]);

        $this->post('/login', [
            'company' => 'login-company',
            'employee_code' => 'EMP-LOGIN-01',
            'name' => 'Login Employee',
            'password' => 'test-password-123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_employee_login_rejects_a_wrong_password(): void
    {
        $tenant = $this->tenant('wrong-password-company');
        $user = $this->user($tenant, 'employee@example.test');
        Employee::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'employee_code' => 'EMP-WRONG-PASSWORD',
            'name' => 'Password Test Employee',
            'status' => 'active',
        ]);

        $this->post('/login', [
            'company' => $tenant->slug,
            'employee_code' => 'EMP-WRONG-PASSWORD',
            'name' => 'Password Test Employee',
            'password' => 'incorrect-password',
        ])->assertSessionHasErrors('employee_code');

        $this->assertGuest();
    }

    public function test_employee_login_rejects_an_inactive_employee(): void
    {
        $tenant = $this->tenant('inactive-employee-company');
        $user = $this->user($tenant, 'inactive-employee@example.test');
        Employee::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'employee_code' => 'EMP-INACTIVE-01',
            'name' => 'Inactive Employee',
            'status' => 'resigned',
        ]);

        $this->post('/login', [
            'company' => $tenant->slug,
            'employee_code' => 'EMP-INACTIVE-01',
            'name' => 'Inactive Employee',
            'password' => 'test-password-123',
        ])->assertSessionHasErrors('employee_code');

        $this->assertGuest();
    }

    public function test_employee_login_rejects_an_inactive_user_account(): void
    {
        $tenant = $this->tenant('inactive-user-company');
        $user = $this->user($tenant, 'inactive-user@example.test');
        $user->update(['is_active' => false]);
        Employee::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'employee_code' => 'EMP-INACTIVE-USER',
            'name' => 'Inactive User Employee',
            'status' => 'active',
        ]);

        $this->post('/login', [
            'company' => $tenant->slug,
            'employee_code' => 'EMP-INACTIVE-USER',
            'name' => 'Inactive User Employee',
            'password' => 'test-password-123',
        ])->assertSessionHasErrors('employee_code');

        $this->assertGuest();
    }

    public function test_login_rejects_employee_identity_from_another_tenant(): void
    {
        $tenantA = $this->tenant('login-company-a');
        $tenantB = $this->tenant('login-company-b');
        $userB = $this->user($tenantB, 'login-b@example.test');
        Employee::create([
            'tenant_id' => $tenantB->id,
            'user_id' => $userB->id,
            'employee_code' => 'EMP-CROSS-TENANT',
            'name' => 'Employee B',
            'status' => 'active',
        ]);

        $this->post('/login', [
            'company' => $tenantA->slug,
            'employee_code' => 'EMP-CROSS-TENANT',
            'name' => 'Employee B',
            'password' => 'test-password-123',
        ])->assertSessionHasErrors('employee_code');

        $this->assertGuest();
    }

    public function test_login_rejects_a_suspended_tenant(): void
    {
        $tenant = $this->tenant('suspended-company', 'suspended');
        $user = $this->user($tenant, 'suspended@example.test');
        Employee::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'employee_code' => 'EMP-SUSPENDED-01',
            'name' => 'Suspended Employee',
            'status' => 'active',
        ]);

        $this->post('/login', [
            'company' => 'suspended-company',
            'employee_code' => 'EMP-SUSPENDED-01',
            'name' => 'Suspended Employee',
            'password' => 'test-password-123',
        ])->assertSessionHasErrors('employee_code');

        $this->assertGuest();
    }

    public function test_authenticated_user_is_logged_out_when_tenant_becomes_suspended(): void
    {
        $tenant = $this->tenant('active-company');
        $user = $this->user($tenant, 'active@example.test');
        $this->actingAs($user);
        $tenant->update(['status' => 'suspended']);

        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_logout_clears_authenticated_session(): void
    {
        $tenant = $this->tenant('logout-company');
        $user = $this->user($tenant, 'logout@example.test');

        $this->actingAs($user)->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
}
