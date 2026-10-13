<?php
namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private NumberingService $numbering,
        private InventoryService $inventory,
        private AuditLogService $audit
    ) {}

    public function create(int $tenantId, array $data): Order
    {
        return DB::transaction(function () use ($tenantId, $data) {
            $subtotal = 0;
            foreach ($data['items'] as &$item) {
                $item['subtotal'] = $item['quantity'] * $item['price'] - ($item['discount'] ?? 0);
                $subtotal += $item['subtotal'];
            }
            $discount = $data['discount'] ?? 0;
            $tax      = $data['tax'] ?? 0;
            $total    = $subtotal - $discount + $tax;

            $order = Order::create([
                'tenant_id'    => $tenantId,
                'order_number' => $this->numbering->next($tenantId, 'order', 'SO'),
                'customer_id'  => $data['customer_id'] ?? null,
                'user_id'      => auth()->id(),
                'status'       => 'draft',
                'subtotal'     => $subtotal,
                'discount'     => $discount,
                'tax'          => $tax,
                'total'        => $total,
                'note'         => $data['note'] ?? null,
            ]);
            $order->items()->createMany($data['items']);

            $this->audit->log('order.created', $order);
            return $order;
        });
    }

    public function confirm(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                // Kurangi stok dari gudang utama (pertama)
                $warehouseId = \App\Models\Warehouse::where('tenant_id', $order->tenant_id)->value('id');
                $this->inventory->deduct($order->tenant_id, $item->product_id, $warehouseId, $item->quantity, Order::class, $order->id);
            }
            $order->update(['status' => 'confirmed']);
            $this->audit->log('order.confirmed', $order);
        });
    }
}
