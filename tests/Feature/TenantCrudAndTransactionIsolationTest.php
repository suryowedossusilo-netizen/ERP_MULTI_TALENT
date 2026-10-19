<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantCrudAndTransactionIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function tenant(string $name, string $slug): Tenant
    {
        return Tenant::create(['name' => $name, 'slug' => $slug, 'status' => 'active']);
    }

    private function userWithPermissions(Tenant $tenant, array $permissionNames): User
    {
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $tenant->name . ' Admin',
            'email' => $tenant->slug . '-admin@example.test',
            'password' => 'temporary-test-password',
            'is_active' => true,
        ]);

        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => $tenant->slug . '-test-role',
            'guard_name' => 'web',
        ]);

        foreach ($permissionNames as $name) {
            $permission = Permission::firstOrCreate(
                ['name' => $name, 'tenant_id' => null],
                ['guard_name' => 'web']
            );
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $user->roles()->attach($role->id);
        return $user;
    }

    public function test_tenant_cannot_read_update_or_delete_another_tenants_employee_user_or_role(): void
    {
        $tenantA = $this->tenant('Company A', 'crud-company-a');
        $tenantB = $this->tenant('Company B', 'crud-company-b');
        $userA = $this->userWithPermissions($tenantA, ['manage-employees', 'manage-users', 'manage-roles']);
        $userB = User::create([
            'tenant_id' => $tenantB->id,
            'name' => 'Private Company B User',
            'email' => 'private-company-b@example.test',
            'password' => 'temporary-test-password',
            'is_active' => true,
        ]);
        $roleB = Role::create([
            'tenant_id' => $tenantB->id,
            'name' => 'private-company-b-role',
            'guard_name' => 'web',
        ]);
        $departmentB = Department::create(['tenant_id' => $tenantB->id, 'name' => 'Private Department B']);
        $employeeB = Employee::create([
            'tenant_id' => $tenantB->id,
            'department_id' => $departmentB->id,
            'employee_code' => 'PRIVATE-B-001',
            'name' => 'Private Employee B',
            'status' => 'active',
        ]);

        $this->actingAs($userA)->get('/employees/' . $employeeB->id)->assertNotFound();
        $this->actingAs($userA)->get('/employees/' . $employeeB->id . '/edit')->assertNotFound();
        $this->actingAs($userA)->put('/employees/' . $employeeB->id, [
            'employee_code' => 'STOLEN-EMPLOYEE',
            'name' => 'Changed by Company A',
            'status' => 'active',
        ])->assertNotFound();
        $this->actingAs($userA)->delete('/employees/' . $employeeB->id)->assertNotFound();

        $this->actingAs($userA)->get('/users/' . $userB->id)->assertNotFound();
        $this->actingAs($userA)->get('/users/' . $userB->id . '/edit')->assertNotFound();
        $this->actingAs($userA)->put('/users/' . $userB->id, [
            'name' => 'Hijacked User',
            'email' => 'hijacked@example.test',
        ])->assertNotFound();
        $this->actingAs($userA)->delete('/users/' . $userB->id)->assertNotFound();

        $this->assertFalse($userA->roles()->whereKey($roleB->id)->exists());\n\n        $this->actingAs($userA)->get('/roles/' . $roleB->id)->assertNotFound();
        $this->actingAs($userA)->get('/roles/' . $roleB->id . '/edit')->assertNotFound();
        $this->actingAs($userA)->put('/roles/' . $roleB->id, ['name' => 'Hijacked Role'])->assertNotFound();
        $this->actingAs($userA)->delete('/roles/' . $roleB->id)->assertNotFound();

        $this->assertDatabaseHas('employees', ['id' => $employeeB->id, 'tenant_id' => $tenantB->id, 'employee_code' => 'PRIVATE-B-001']);
        $this->assertDatabaseHas('users', ['id' => $userB->id, 'tenant_id' => $tenantB->id, 'email' => 'private-company-b@example.test']);
        $this->assertDatabaseHas('roles', ['id' => $roleB->id, 'tenant_id' => $tenantB->id, 'name' => 'private-company-b-role']);
    }

    public function test_tenant_cannot_view_another_tenants_invoice_or_order(): void
    {
        $tenantA = $this->tenant('Sales Company A', 'sales-company-a');
        $tenantB = $this->tenant('Sales Company B', 'sales-company-b');
        $userA = $this->userWithPermissions($tenantA, ['manage-sales']);

        $orderB = Order::create([
            'tenant_id' => $tenantB->id,
            'order_number' => 'SO-PRIVATE-B-001',
            'status' => 'draft',
            'subtotal' => 100,
            'discount' => 0,
            'tax' => 0,
            'total' => 100,
        ]);
        $invoiceB = Invoice::create([
            'tenant_id' => $tenantB->id,
            'invoice_number' => 'INV-PRIVATE-B-001',
            'order_id' => $orderB->id,
            'issue_date' => now()->toDateString(),
            'amount' => 100,
            'paid' => 0,
            'status' => 'unpaid',
        ]);

        $this->actingAs($userA)->get('/sales/orders/' . $orderB->id)->assertNotFound();
        $this->actingAs($userA)->delete('/sales/orders/' . $orderB->id)->assertNotFound();
        $this->actingAs($userA)->get('/sales/invoices/' . $invoiceB->id)->assertNotFound();

        $this->assertDatabaseHas('orders', ['id' => $orderB->id, 'tenant_id' => $tenantB->id, 'status' => 'draft']);
        $this->assertDatabaseHas('invoices', ['id' => $invoiceB->id, 'tenant_id' => $tenantB->id, 'status' => 'unpaid']);
    }

    public function test_payment_cannot_be_recorded_against_another_tenants_invoice(): void
    {
        $tenantA = $this->tenant('Payment Company A', 'payment-company-a');
        $tenantB = $this->tenant('Payment Company B', 'payment-company-b');
        $userA = $this->userWithPermissions($tenantA, ['manage-sales']);
        $orderB = Order::create([
            'tenant_id' => $tenantB->id,
            'order_number' => 'SO-PAYMENT-B-001',
            'status' => 'confirmed',
            'subtotal' => 150,
            'discount' => 0,
            'tax' => 0,
            'total' => 150,
        ]);
        $invoiceB = Invoice::create([
            'tenant_id' => $tenantB->id,
            'invoice_number' => 'INV-PAYMENT-B-001',
            'order_id' => $orderB->id,
            'issue_date' => now()->toDateString(),
            'amount' => 150,
            'paid' => 0,
            'status' => 'unpaid',
        ]);

        $this->actingAs($userA)->post('/sales/payments', [
            'invoice_id' => $invoiceB->id,
            'amount' => 50,
            'method' => 'cash',
            'payment_date' => now()->toDateString(),
        ])->assertNotFound();

        $this->assertDatabaseHas('invoices', ['id' => $invoiceB->id, 'tenant_id' => $tenantB->id, 'paid' => 0]);
        $this->assertDatabaseMissing('payments', ['invoice_id' => $invoiceB->id, 'tenant_id' => $tenantA->id]);
    }

    public function test_stock_adjustment_rejects_products_or_warehouses_from_another_tenant(): void
    {
        $tenantA = $this->tenant('Stock Company A', 'stock-company-a');
        $tenantB = $this->tenant('Stock Company B', 'stock-company-b');
        $userA = $this->userWithPermissions($tenantA, ['manage-inventory']);
        $productB = Product::create([
            'tenant_id' => $tenantB->id,
            'sku' => 'PRIVATE-SKU-B-001',
            'name' => 'Private Product B',
            'purchase_price' => 1,
            'selling_price' => 2,
            'is_active' => true,
        ]);
        $warehouseA = Warehouse::create([
            'tenant_id' => $tenantA->id,
            'code' => 'WH-STOCK-A-001',
            'name' => 'Company A Warehouse',
        ]);
        $warehouseB = Warehouse::create([
            'tenant_id' => $tenantB->id,
            'code' => 'WH-STOCK-B-001',
            'name' => 'Company B Warehouse',
        ]);

        $this->actingAs($userA)->post('/inventory/adjust', [
            'product_id' => $productB->id,
            'warehouse_id' => $warehouseA->id,
            'type' => 'in',
            'quantity' => 4,
        ])->assertSessionHasErrors('product_id');

        $productA = Product::create([
            'tenant_id' => $tenantA->id,
            'sku' => 'LOCAL-SKU-A-001',
            'name' => 'Company A Product',
            'purchase_price' => 1,
            'selling_price' => 2,
            'is_active' => true,
        ]);
        $this->actingAs($userA)->post('/inventory/adjust', [
            'product_id' => $productA->id,
            'warehouse_id' => $warehouseB->id,
            'type' => 'in',
            'quantity' => 4,
        ])->assertSessionHasErrors('warehouse_id');

        $this->assertDatabaseMissing('stocks', ['tenant_id' => $tenantA->id, 'product_id' => $productB->id]);
        $this->assertDatabaseMissing('stocks', ['tenant_id' => $tenantA->id, 'product_id' => $productA->id, 'warehouse_id' => $warehouseB->id]);
        $this->assertDatabaseMissing('stock_movements', ['tenant_id' => $tenantA->id]);
    }

    public function test_invoice_creation_rejects_another_tenants_order(): void
    {
        $tenantA = $this->tenant('Invoice Company A', 'invoice-company-a');
        $tenantB = $this->tenant('Invoice Company B', 'invoice-company-b');
        $userA = $this->userWithPermissions($tenantA, ['manage-sales']);
        $orderB = Order::create([
            'tenant_id' => $tenantB->id,
            'order_number' => 'SO-INVOICE-FOREIGN-001',
            'status' => 'confirmed',
            'subtotal' => 75,
            'discount' => 0,
            'tax' => 0,
            'total' => 75,
        ]);

        $this->actingAs($userA)->post('/sales/invoices', [
            'order_id' => $orderB->id,
            'due_date' => now()->addDays(14)->toDateString(),
        ])->assertNotFound();

        $this->assertDatabaseMissing('invoices', [
            'tenant_id' => $tenantA->id,
            'order_id' => $orderB->id,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderB->id,
            'tenant_id' => $tenantB->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_sales_order_creation_rejects_another_tenants_customer(): void
    {
        $tenantA = $this->tenant('Order Company A', 'order-company-a');
        $tenantB = $this->tenant('Order Company B', 'order-company-b');
        $userA = $this->userWithPermissions($tenantA, ['manage-sales']);
        $customerB = Customer::create([
            'tenant_id' => $tenantB->id,
            'code' => 'PRIVATE-CUSTOMER-B-001',
            'name' => 'Private Customer B',
        ]);
        $productA = Product::create([
            'tenant_id' => $tenantA->id,
            'sku' => 'ORDER-LOCAL-SKU-A',
            'name' => 'Local Product A',
            'purchase_price' => 1,
            'selling_price' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($userA)->post('/sales/orders', [
            'customer_id' => $customerB->id,
            'items' => [['product_id' => $productA->id, 'quantity' => 1]],
        ])->assertNotFound();

        $this->assertDatabaseMissing('orders', [
            'tenant_id' => $tenantA->id,
            'customer_id' => $customerB->id,
        ]);
    }

}
