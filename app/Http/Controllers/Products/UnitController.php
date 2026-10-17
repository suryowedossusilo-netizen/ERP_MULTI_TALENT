<?php
namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    private function owned(Unit $item): Unit
    {
        abort_unless(tenant() && (int) $item->tenant_id === (int) tenant()->id, 404);
        return $item;
    }

    public function index()
    {
        $items = Unit::where('tenant_id', tenant()->id)->paginate(15);
        return view('products.units.index', compact('items'));
    }

    public function create() { return view('products.units.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Unit::create($data);
        return redirect()->route('products.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function show(Unit $item) { $item = $this->owned($item); return view('products.units.show', compact('item')); }

    public function edit(Unit $item) { $item = $this->owned($item); return view('products.units.edit', compact('item')); }

    public function update(Request $request, Unit $item)
    {
        $item = $this->owned($item);
        $item->update($request->validate([
            'name' => 'required',
        ]));
        return redirect()->route('products.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Unit $item)
    {
        $item = $this->owned($item);
        $item->delete();
        return back()->with('success', 'Satuan berhasil dihapus.');
    }
}
