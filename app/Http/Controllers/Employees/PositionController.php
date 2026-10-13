<?php
namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $items = Position::where('tenant_id', tenant()->id)->paginate(15);
        return view('employees.positions.index', compact('items'));
    }

    public function create() { return view('employees.positions.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Position::create($data);
        return redirect()->route('employees.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function show(Position $item) { return view('employees.positions.show', compact('item')); }

    public function edit(Position $item) { return view('employees.positions.edit', compact('item')); }

    public function update(Request $request, Position $item)
    {
        $item->update($request->validate([
            'name' => 'required',
        ]));
        return redirect()->route('employees.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $item)
    {
        $item->delete();
        return back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
