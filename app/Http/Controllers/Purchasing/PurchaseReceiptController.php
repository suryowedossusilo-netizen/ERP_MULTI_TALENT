<?php
namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PurchaseReceiptController extends Controller
{
    public function __construct(private InventoryService $inventoryService) {}

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        abort_unless(tenant() && (int) $purchaseOrder->tenant_id === (int) tenant()->id, 404);

        $data = $request->validate([
            'warehouse_id' => 'required|integer',
            'items'        => 'required|array|min:1',
            'items.*.id'   => [
                'required',
                'integer',
                Rule::exists('purchase_order_items', 'id')->where(
                    fn ($query) => $query->where('purchase_order_id', $purchaseOrder->id)
                ),
            ],
            'items.*.qty'  => 'required|numeric|min:0.001',
        ]);
        abort_unless(
            Warehouse::where('tenant_id', tenant()->id)->whereKey($data['warehouse_id'])->exists(),
            404
        );

        $this->inventoryService->receivePurchaseOrder($purchaseOrder, $data);
        return back()->with('success', 'Barang diterima & stok masuk.');
    }
}
