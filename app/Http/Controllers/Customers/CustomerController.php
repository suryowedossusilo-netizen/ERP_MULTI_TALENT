<?php
namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private function owned(Customer $item): Customer
    {
        abort_unless(tenant() && (int) $item->tenant_id === (int) tenant()->id, 404);
        return $item;
    }

    public function index()
    {
        $items = Customer::where('tenant_id', tenant()->id)->paginate(15);
        return view('customers.index', compact('items'));
    }

    public function create() { return view('customers.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Customer::create($data);
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $item) { $item = $this->owned($item); return view('customers.show', compact('item')); }

    public function edit(Customer $item) { $item = $this->owned($item); return view('customers.edit', compact('item')); }

    public function update(Request $request, Customer $item)
    {
        $item = $this->owned($item);
        $item->update($request->validate([
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]));
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $item)
    {
        $item = $this->owned($item);
        $item->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
