<?php
namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $items = Department::where('tenant_id', tenant()->id)->paginate(15);
        return view('employees.departments.index', compact('items'));
    }

    public function create() { return view('employees.departments.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Department::create($data);
        return redirect()->route('employees.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function show(Department $item) { return view('employees.departments.show', compact('item')); }

    public function edit(Department $item) { return view('employees.departments.edit', compact('item')); }

    public function update(Request $request, Department $item)
    {
        $item->update($request->validate([
            'name' => 'required',
        ]));
        return redirect()->route('employees.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $item)
    {
        $item->delete();
        return back()->with('success', 'Departemen berhasil dihapus.');
    }
}
