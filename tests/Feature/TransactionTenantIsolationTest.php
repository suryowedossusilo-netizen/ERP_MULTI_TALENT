<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_order_rejects_a_product_owned_by_another_tenant(): void
    {
        $tenantA = Tenant::create(['name' => 'Sales Company A', 'slug' => 'sales-a', 'status' => 'active']);
        $tenantB = Tenant::create(['name' => 'Sales Company B', 'slug' => 'sales-b', 'status' => 'active']);

        $userA = User::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Sales Admin',
            'email' => 'sales-admin@example.test',
            'password' => 'temporary-test-password',
            'is_active' => true,
        ]);

        $permission = Permission::firstOrCreate(
            ['name' => 'manage-sales', 'tenant_id' => null],
            ['guard_name' => 'web']
        );
        $role = Role::create([
            'tenant_id' => $tenantA->id,
            'name' => 'sales-admin-test',
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($permission->id);
        $userA->roles()->attach($role->id);

        $foreignProduct = Product::create([
            'tenant_id' => $tenantB->id,
            'sku' => 'SALES-FOREIGN-01',
            'name' => 'Private Product',
            'purchase_price' => 1,
            'selling_price' => 2,
            'min_stock' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($userA)->post('/sales/orders', [
            'items' => [
                ['product_id' => $foreignProduct->id, 'quantity' => 1, 'price' => 2],
            ],
        ])->assertNotFound();

        $this->assertDatabaseMissing('orders', ['tenant_id' => $tenantA->id]);
        $this->assertDatabaseHas('products', [
            'id' => $foreignProduct->id,
            'tenant_id' => $tenantB->id,
        ]);
    }
}
