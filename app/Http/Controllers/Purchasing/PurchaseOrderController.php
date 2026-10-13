<?php
namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $items = PurchaseOrder::where('tenant_id', tenant()->id)->paginate(15);
        return view('purchasing.index', compact('items'));
    }

    public function create() { return view('purchasing.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'po_number' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        PurchaseOrder::create($data);
        return redirect()->route('purchasing.index')->with('success', 'PO berhasil ditambahkan.');
    }

    public function show(PurchaseOrder $item) { return view('purchasing.show', compact('item')); }

    public function edit(PurchaseOrder $item) { return view('purchasing.edit', compact('item')); }

    public function update(Request $request, PurchaseOrder $item)
    {
        $item->update($request->validate([
            'po_number' => 'required',
        ]));
        return redirect()->route('purchasing.index')->with('success', 'PO berhasil diperbarui.');
    }

    public function destroy(PurchaseOrder $item)
    {
        $item->delete();
        return back()->with('success', 'PO berhasil dihapus.');
    }
}
