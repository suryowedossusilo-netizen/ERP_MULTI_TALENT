<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_order_form_persists_required_date_and_optional_fields(): void
    {
        $tenant = Tenant::create([
            'name' => 'Purchase Persistence Company',
            'slug' => 'purchase-persistence',
            'status' => 'active',
        ]);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Purchase Admin',
            'email' => 'purchase-persistence@example.test',
            'password' => 'temporary-test-password',
            'is_active' => true,
        ]);
        $permission = Permission::firstOrCreate(
            ['name' => 'manage-purchasing', 'tenant_id' => null],
            ['guard_name' => 'web']
        );
        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'purchase-persistence-test',
            'guard_name' => 'web',
        ]);
        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $this->actingAs($user)->post('/purchasing/purchase-orders', [
            'po_number' => 'PO-PERSIST-001',
            'order_date' => '2026-10-19',
            'tax' => 12500,
            'note' => 'Test persistence',
        ])->assertRedirect(route('purchasing.purchase-orders.index'));

        // SQLite returns DATE columns as midnight timestamps in database assertions.
        $this->assertDatabaseHas('purchase_orders', [
            'tenant_id' => $tenant->id,
            'po_number' => 'PO-PERSIST-001',
            'order_date' => '2026-10-19 00:00:00',
            'tax' => 12500,
            'total' => 12500,
            'note' => 'Test persistence',
            'status' => 'draft',
        ]);
    }
}
