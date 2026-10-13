<?php
namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
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

    public function show(Unit $item) { return view('products.units.show', compact('item')); }

    public function edit(Unit $item) { return view('products.units.edit', compact('item')); }

    public function update(Request $request, Unit $item)
    {
        $item->update($request->validate([
            'name' => 'required',
        ]));
        return redirect()->route('products.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Unit $item)
    {
        $item->delete();
        return back()->with('success', 'Satuan berhasil dihapus.');
    }
}
