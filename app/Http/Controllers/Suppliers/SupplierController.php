<?php
namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $items = Supplier::where('tenant_id', tenant()->id)->paginate(15);
        return view('suppliers.index', compact('items'));
    }

    public function create() { return view('suppliers.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Supplier::create($data);
        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function show(Supplier $item) { return view('suppliers.show', compact('item')); }

    public function edit(Supplier $item) { return view('suppliers.edit', compact('item')); }

    public function update(Request $request, Supplier $item)
    {
        $item->update($request->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]));
        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil diperbarui.');
    }

    public function destroy(Supplier $item)
    {
        $item->delete();
        return back()->with('success', 'Pemasok berhasil dihapus.');
    }
}
