<?php
namespace App\\Http\\Controllers\\Employees;

use App\\Http\\Controllers\\Controller;
use App\\Models\\Department;
use Illuminate\\Http\\Request;

class DepartmentController extends Controller
{
    private function tenantId(): int
    {
        abort_unless(tenant(), 403, 'Tenant tidak ditemukan.');
        return (int) tenant()->id;
    }

    private function owned(Department $item): Department
    {
        abort_unless((int) $item->tenant_id === $this->tenantId(), 404);
        return $item;
    }

    public function index()
    {
        $items = Department::where('tenant_id', $this->tenantId())->paginate(15);
        return view('employees.departments.index', compact('items'));
    }

    public function create() { return view('employees.departments.create'); }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $data['tenant_id'] = $this->tenantId();
        Department::create($data);
        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function show(Department $item)
    {
        $item = $this->owned($item);
        return view('employees.departments.show', compact('item'));
    }

    public function edit(Department $item)
    {
        $item = $this->owned($item);
        return view('employees.departments.edit', compact('item'));
    }

    public function update(Request $request, Department $item)
    {
        $this->owned($item)->update($request->validate(['name' => 'required|string|max:255']));
        return redirect()->route('departments.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $item)
    {
        $this->owned($item)->delete();
        return back()->with('success', 'Departemen berhasil dihapus.');
    }
}
