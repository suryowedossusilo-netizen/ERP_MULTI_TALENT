<?php
namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function owned(Category $item): Category
    {
        abort_unless(tenant() && (int) $item->tenant_id === (int) tenant()->id, 404);
        return $item;
    }

    public function index()
    {
        $items = Category::where('tenant_id', tenant()->id)->paginate(15);
        return view('products.categories.index', compact('items'));
    }

    public function create() { return view('products.categories.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Category::create($data);
        return redirect()->route('products.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(Category $item) { $item = $this->owned($item); return view('products.categories.show', compact('item')); }

    public function edit(Category $item) { $item = $this->owned($item); return view('products.categories.edit', compact('item')); }

    public function update(Request $request, Category $item)
    {
        $item = $this->owned($item);
        $item->update($request->validate([
            'name' => 'required',
        ]));
        return redirect()->route('products.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $item)
    {
        $item = $this->owned($item);
        $item->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
