<?php
namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            if (!empty($data['customer_id'])) {
                Customer::where('tenant_id', $tenantId)->findOrFail($data['customer_id']);
            }

            $subtotal = 0.0;
            $itemsToCreate = [];
            foreach ($data['items'] as $item) {
                $product = Product::where('tenant_id', $tenantId)
                    ->where('is_active', true)
                    ->findOrFail($item['product_id']);

                $quantity = (float) $item['quantity'];
                // Always use the selling price stored by the tenant, never a client-supplied price.
                $price = round((float) $product->selling_price, 2);
                $lineDiscount = round((float) ($item['discount'] ?? 0), 2);
                $gross = round($quantity * $price, 2);
                if ($lineDiscount < 0 || $lineDiscount > $gross) {
                    throw ValidationException::withMessages([
                        'items' => 'Diskon setiap barang tidak boleh melebihi nilai barang.',
                    ]);
                }
                $lineSubtotal = round($gross - $lineDiscount, 2);
                $subtotal += $lineSubtotal;
                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $lineDiscount,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $discount = round((float) ($data['discount'] ?? 0), 2);
            $tax = round((float) ($data['tax'] ?? 0), 2);
            if ($discount < 0 || $discount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount' => 'Diskon order tidak boleh melebihi subtotal.',
                ]);
            }
            $total = round($subtotal - $discount + $tax, 2);
            if ($total < 0) {
                throw ValidationException::withMessages(['tax' => 'Total order tidak boleh negatif.']);
            }

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
            $order->items()->createMany($itemsToCreate);

            $this->audit->log('order.created', $order);
            return $order;
        });
    }

    public function confirm(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::where('tenant_id', $order->tenant_id)
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->status !== 'draft') {
                throw ValidationException::withMessages([
                    'order' => 'Order hanya dapat dikonfirmasi satu kali dan harus berstatus draft.',
                ]);
            }

            $items = $lockedOrder->items()->get();
            $warehouseId = Warehouse::where('tenant_id', $lockedOrder->tenant_id)->value('id');
            if (!$warehouseId && $items->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'order' => 'Tenant belum memiliki gudang untuk pengurangan stok.',
                ]);
            }

            foreach ($items as $item) {
                $product = Product::where('tenant_id', $lockedOrder->tenant_id)->findOrFail($item->product_id);
                $this->inventory->deduct($lockedOrder->tenant_id, $product->id, $warehouseId, $item->quantity, Order::class, $lockedOrder->id);
            }

            $lockedOrder->update(['status' => 'confirmed']);
            $this->audit->log('order.confirmed', $lockedOrder);
        });
    }
}
