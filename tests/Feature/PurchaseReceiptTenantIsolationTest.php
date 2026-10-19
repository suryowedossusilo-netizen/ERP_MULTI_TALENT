<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseReceiptTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_receipt_rejects_item_that_belongs_to_a_different_purchase_order(): void
    {
        $tenant = Tenant::create([
            'name' => 'Receipt Company',
            'slug' => 'receipt-company',
            'status' => 'active',
        ]);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Purchase Admin',
            'email' => 'receipt-admin@example.test',
            'password' => 'temporary-test-password',
            'is_active' => true,
        ]);
        $permission = Permission::firstOrCreate(
            ['name' => 'manage-purchasing', 'tenant_id' => null],
            ['guard_name' => 'web']
        );
        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'receipt-admin-test',
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $targetOrder = PurchaseOrder::create([
            'tenant_id' => $tenant->id,
            'po_number' => 'PO-RECEIPT-TARGET',
            'status' => 'sent',
            'subtotal' => 10,
            'tax' => 0,
            'total' => 10,
            'order_date' => now()->toDateString(),
        ]);
        $otherOrder = PurchaseOrder::create([
            'tenant_id' => $tenant->id,
            'po_number' => 'PO-RECEIPT-OTHER',
            'status' => 'sent',
            'subtotal' => 10,
            'tax' => 0,
            'total' => 10,
            'order_date' => now()->toDateString(),
        ]);
        $foreignOrderItem = PurchaseOrderItem::create([
            'purchase_order_id' => $otherOrder->id,
            'product_id' => null,
            'quantity' => 5,
            'price' => 2,
            'subtotal' => 10,
            'received_quantity' => 0,
        ]);
        $warehouse = Warehouse::create([
            'tenant_id' => $tenant->id,
            'code' => 'RECEIPT-WH-01',
            'name' => 'Receipt Warehouse',
        ]);

        $this->actingAs($user)
            ->post('/purchasing/purchase-orders/' . $targetOrder->id . '/receipt', [
                'warehouse_id' => $warehouse->id,
                'items' => [
                    ['id' => $foreignOrderItem->id, 'qty' => 2],
                ],
            ])
            ->assertSessionHasErrors('items.0.id');

        $this->assertDatabaseHas('purchase_order_items', [
            'id' => $foreignOrderItem->id,
            'purchase_order_id' => $otherOrder->id,
            'received_quantity' => 0,
        ]);
        $this->assertDatabaseMissing('stock_movements', [
            'tenant_id' => $tenant->id,
        ]);
    }
}
