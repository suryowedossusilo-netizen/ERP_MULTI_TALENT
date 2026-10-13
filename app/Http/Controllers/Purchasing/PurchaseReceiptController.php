<?php
namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class PurchaseReceiptController extends Controller
{
    public function __construct(private InventoryService $inventoryService) {}

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $data = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'items'        => 'required|array|min:1',
            'items.*.id'   => 'required|exists:purchase_order_items,id',
            'items.*.qty'  => 'required|numeric|min:0.001',
        ]);
        $this->inventoryService->receivePurchaseOrder($purchaseOrder, $data);
        return back()->with('success', 'Barang diterima & stok masuk.');
    }
}
