<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::where('tenant_id', tenant()->id)
            ->with(['product', 'warehouse'])->paginate(20);

        return view('inventory.index', compact('stocks'));
    }

    public function create()
    {
        $products = Product::where('tenant_id', tenant()->id)
            ->where('is_active', true)
            ->get();
        $warehouses = Warehouse::where('tenant_id', tenant()->id)->get();

        return view('inventory.adjust', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'   => ['required', 'integer', Rule::exists('products', 'id')->where('tenant_id', tenant()->id)],
            'warehouse_id' => ['required', 'integer', Rule::exists('warehouses', 'id')->where('tenant_id', tenant()->id)],
            'type'         => 'required|in:in,out',
            'quantity'     => 'required|numeric|min:0.001',
            'note'         => 'nullable|string',
        ]);

        $stock = Stock::firstOrCreate(
            [
                'tenant_id' => tenant()->id,
                'product_id' => $data['product_id'],
                'warehouse_id' => $data['warehouse_id'],
            ],
            ['quantity' => 0]
        );

        $before = $stock->quantity;
        $delta = $data['type'] === 'in' ? $data['quantity'] : -$data['quantity'];

        abort_if($before + $delta < 0, 422, 'Stok tidak mencukupi untuk pengurangan.');

        $stock->increment('quantity', $delta);

        \App\Models\StockMovement::create([
            'tenant_id' => tenant()->id,
            'product_id' => $data['product_id'],
            'warehouse_id' => $data['warehouse_id'],
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'stock_before' => $before,
            'stock_after' => $before + $delta,
            'reference_type' => 'manual_adjustment',
            'note' => $data['note'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('inventory.index')->with('success', 'Stok berhasil disesuaikan.');
    }
}
