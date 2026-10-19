<?php
namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PurchaseOrderController extends Controller
{
    private function owned(PurchaseOrder $item): PurchaseOrder
    {
        abort_unless(tenant() && (int) $item->tenant_id === (int) tenant()->id, 404);
        return $item;
    }

    public function index()
    {
        $items = PurchaseOrder::where('tenant_id', tenant()->id)->paginate(15);
        return view('purchasing.index', compact('items'));
    }

    public function create()
    {
        return view('purchasing.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'po_number' => ['required', 'string', 'max:100', Rule::unique('purchase_orders', 'po_number')],
            'order_date' => 'required|date',
            'tax' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:5000',
        ]);

        $data['tenant_id'] = tenant()->id;
        $data['status'] = 'draft';
        $data['subtotal'] = 0;
        $data['tax'] = (float) ($data['tax'] ?? 0);
        $data['total'] = $data['subtotal'] + $data['tax'];
        PurchaseOrder::create($data);

        return redirect()->route('purchasing.purchase-orders.index')->with('success', 'PO berhasil ditambahkan.');
    }

    public function show(PurchaseOrder $item)
    {
        $item = $this->owned($item);
        return view('purchasing.show', compact('item'));
    }

    public function edit(PurchaseOrder $item)
    {
        $item = $this->owned($item);
        return view('purchasing.create', compact('item'));
    }

    public function update(Request $request, PurchaseOrder $item)
    {
        $item = $this->owned($item);
        $data = $request->validate([
            'po_number' => [
                'required', 'string', 'max:100',
                Rule::unique('purchase_orders', 'po_number')->ignore($item->id),
            ],
            'order_date' => 'required|date',
            'tax' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:5000',
        ]);

        $data['tax'] = (float) ($data['tax'] ?? 0);
        $data['total'] = (float) $item->subtotal + $data['tax'];
        $item->update($data);

        return redirect()->route('purchasing.purchase-orders.index')->with('success', 'PO berhasil diperbarui.');
    }

    public function destroy(PurchaseOrder $item)
    {
        $item = $this->owned($item);
        $item->delete();

        return redirect()->route('purchasing.purchase-orders.index')->with('success', 'PO berhasil dihapus.');
    }
}
