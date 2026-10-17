<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private function owned(Warehouse $item): Warehouse
    {
        abort_unless(tenant() && (int) $item->tenant_id === (int) tenant()->id, 404);
        return $item;
    }

    public function index()
    {
        $items = Warehouse::where('tenant_id', tenant()->id)->paginate(15);
        return view('inventory.warehouses.index', compact('items'));
    }

    public function create() { return view('inventory.warehouses.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Warehouse::create($data);
        return redirect()->route('inventory.index')->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function show(Warehouse $item) { $item = $this->owned($item); return view('inventory.warehouses.show', compact('item')); }

    public function edit(Warehouse $item) { $item = $this->owned($item); return view('inventory.warehouses.edit', compact('item')); }

    public function update(Request $request, Warehouse $item)
    {
        $item = $this->owned($item);
        $item->update($request->validate([
            'code' => 'required',
            'name' => 'required',
        ]));
        return redirect()->route('inventory.index')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Warehouse $item)
    {
        $item = $this->owned($item);
        $item->delete();
        return back()->with('success', 'Gudang berhasil dihapus.');
    }
}
