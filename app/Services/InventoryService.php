<?php
namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function add(int $tenantId, int $productId, ?int $warehouseId, float $qty, ?string $refType = null, ?int $refId = null, ?string $note = null): Stock
    {
        return DB::transaction(function () use ($tenantId, $productId, $warehouseId, $qty, $refType, $refId, $note) {
            $stock = Stock::lockForUpdate()->firstOrCreate(
                ['tenant_id' => $tenantId, 'product_id' => $productId, 'warehouse_id' => $warehouseId],
                ['quantity' => 0]
            );
            $before = $stock->quantity;
            $stock->increment('quantity', $qty);
            StockMovement::create([
                'tenant_id' => $tenantId, 'product_id' => $productId, 'warehouse_id' => $warehouseId,
                'type' => 'in', 'quantity' => $qty, 'stock_before' => $before, 'stock_after' => $before + $qty,
                'reference_type' => $refType, 'reference_id' => $refId, 'note' => $note, 'user_id' => auth()->id(),
            ]);
            return $stock;
        });
    }

    public function deduct(int $tenantId, int $productId, ?int $warehouseId, float $qty, ?string $refType = null, ?int $refId = null): Stock
    {
        return DB::transaction(function () use ($tenantId, $productId, $warehouseId, $qty, $refType, $refId) {
            $stock = Stock::lockForUpdate()->firstOrCreate(
                ['tenant_id' => $tenantId, 'product_id' => $productId, 'warehouse_id' => $warehouseId],
                ['quantity' => 0]
            );
            $before = $stock->quantity;
            if ($before < $qty) abort(422, 'Stok tidak mencukupi.');
            $stock->decrement('quantity', $qty);
            StockMovement::create([
                'tenant_id' => $tenantId, 'product_id' => $productId, 'warehouse_id' => $warehouseId,
                'type' => 'out', 'quantity' => $qty, 'stock_before' => $before, 'stock_after' => $before - $qty,
                'reference_type' => $refType, 'reference_id' => $refId, 'user_id' => auth()->id(),
            ]);
            return $stock;
        });
    }

    public function receivePurchaseOrder(PurchaseOrder $po, array $data): void
    {
        DB::transaction(function () use ($po, $data) {
            foreach ($data['items'] as $row) {
                $item = $po->items()->findOrFail($row['id']);
                $qty  = min($row['qty'], $item->quantity - $item->received_quantity);
                if ($qty <= 0) continue;
                $this->add($po->tenant_id, $item->product_id, $data['warehouse_id'], $qty, PurchaseOrder::class, $po->id, 'Penerimaan ' . $po->po_number);
                $item->increment('received_quantity', $qty);
            }
            if ($po->items()->whereColumn('received_quantity', '<', 'quantity')->doesntExist()) {
                $po->update(['status' => 'received']);
            }
        });
    }
}
