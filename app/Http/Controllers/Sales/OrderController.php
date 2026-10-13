<?php
namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index()
    {
        $orders = Order::where('tenant_id', tenant()->id)->with('customer')->latest()->paginate(15);
        return view('sales.orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = \App\Models\Customer::where('tenant_id', tenant()->id)->get();
        $products  = \App\Models\Product::where('tenant_id', tenant()->id)->where('is_active', true)->get();
        return view('sales.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|integer',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.price'      => 'nullable|numeric|min:0',
            'items.*.discount'   => 'nullable|numeric|min:0',
            'discount'    => 'nullable|numeric|min:0',
            'tax'         => 'nullable|numeric|min:0',
            'note'        => 'nullable|string',
        ]);

        $order = $this->orderService->create(tenant()->id, $data);
        return redirect()->route('sales.orders.show', $order)->with('success', 'Order ' . $order->order_number . ' dibuat.');
    }

    public function show(Order $order)
    {
        $order = Order::where('tenant_id', tenant()->id)->whereKey($order->id)->firstOrFail();
        $order->load('items.product', 'customer');
        return view('sales.orders.show', compact('order'));
    }

    public function confirm(Order $order)
    {
        $order = Order::where('tenant_id', tenant()->id)->whereKey($order->id)->firstOrFail();
        $this->orderService->confirm($order);
        return back()->with('success', 'Order dikonfirmasi & stok dikurangi.');
    }

    public function destroy(Order $order)
    {
        $order = Order::where('tenant_id', tenant()->id)->whereKey($order->id)->firstOrFail();
        if ($order->status !== 'draft') {
            throw ValidationException::withMessages(['order' => 'Hanya order berstatus draft yang dapat dihapus.']);
        }
        $order->delete();
        return redirect()->route('sales.orders.index')->with('success', 'Order dihapus.');
    }
}
