<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\PurchaseOrder;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderRouteRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_order_edit_uses_the_existing_form_view(): void
    {
        $tenant = Tenant::create([
            'name' => 'Purchase Company',
            'slug' => 'purchase-company',
            'status' => 'active',
        ]);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Purchase Admin',
            'email' => 'purchase-admin@example.test',
            'password' => 'temporary-test-password',
            'is_active' => true,
        ]);
        $permission = Permission::firstOrCreate(
            ['name' => 'manage-purchasing', 'tenant_id' => null],
            ['guard_name' => 'web']
        );
        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'purchase-admin-test',
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $order = PurchaseOrder::create([
            'tenant_id' => $tenant->id,
            'po_number' => 'PO-EDIT-001',
            'status' => 'draft',
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
            'order_date' => now()->toDateString(),
        ]);

        $this->actingAs($user)
            ->get('/purchasing/purchase-orders/' . $order->id . '/edit')
            ->assertOk()
            ->assertViewIs('purchasing.create')
            ->assertViewHas('item', fn ($item) => $item->is($order));
    }
}
