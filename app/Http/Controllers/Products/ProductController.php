<?php
namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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

    public function show(Product $item) { return view('products.show', compact('item')); }

    public function edit(Product $item) { return view('products.edit', compact('item')); }

    public function update(Request $request, Product $item)
    {
        $item->update($request->validate([
            'sku' => 'required',
            'name' => 'required',
            'selling_price' => 'required',
        ]));
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $item)
    {
        $item->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
