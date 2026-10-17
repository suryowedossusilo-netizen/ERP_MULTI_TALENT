<?php
namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function owned(Product $item): Product
    {
        abort_unless(tenant() && (int) $item->tenant_id === (int) tenant()->id, 404);
        return $item;
    }

    public function index()
    {
        $items = Product::where('tenant_id', tenant()->id)->paginate(15);
        return view('products.index', compact('items'));
    }

    public function create() { return view('products.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'required',
            'name' => 'required',
            'selling_price' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $item) { $item = $this->owned($item); return view('products.show', compact('item')); }

    public function edit(Product $item) { $item = $this->owned($item); return view('products.edit', compact('item')); }

    public function update(Request $request, Product $item)
    {
        $item = $this->owned($item);
        $item->update($request->validate([
            'sku' => 'required',
            'name' => 'required',
            'selling_price' => 'required',
        ]));
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $item)
    {
        $item = $this->owned($item);
        $item->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
