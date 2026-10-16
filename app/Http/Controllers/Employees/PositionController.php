<?php
namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    private function tenantId(): int
    {
        abort_unless(tenant(), 403, 'Tenant tidak ditemukan.');
        return (int) tenant()->id;
    }

    private function owned(Position $item): Position
    {
        abort_unless((int) $item->tenant_id === $this->tenantId(), 404);
        return $item;
    }

    public function index()
    {
        $items = Position::where('tenant_id', $this->tenantId())->paginate(15);
        return view('employees.positions.index', compact('items'));
    }

    public function create() { return view('employees.positions.create'); }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $data['tenant_id'] = $this->tenantId();
        Position::create($data);
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function show(Position $item)
    {
        $item = $this->owned($item);
        return view('employees.positions.show', compact('item'));
    }

    public function edit(Position $item)
    {
        $item = $this->owned($item);
        return view('employees.positions.edit', compact('item'));
    }

    public function update(Request $request, Position $item)
    {
        $this->owned($item)->update($request->validate(['name' => 'required|string|max:255']));
        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Position $item)
    {
        $this->owned($item)->delete();
        return back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
