<?php
namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $items = Employee::where('tenant_id', tenant()->id)->with(['department', 'position'])->paginate(15);
        return view('employees.index', compact('items'));
    }

    public function create()
    {
        $departments = Department::where('tenant_id', tenant()->id)->get();
        $positions   = Position::where('tenant_id', tenant()->id)->get();
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_code' => 'required|string|unique:employees,employee_code',
            'name'          => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'position_id'   => 'nullable|exists:positions,id',
            'phone'         => 'nullable|string',
            'email'         => 'nullable|email',
            'join_date'     => 'nullable|date',
            'salary'        => 'nullable|numeric|min:0',
        ]);
        $data['tenant_id'] = tenant()->id;
        Employee::create($data);
        return redirect()->route('employees.index')->with('success', 'Karyawan ditambahkan.');
    }

    public function show(Employee $item) { return view('employees.show', compact('item')); }
    public function edit(Employee $item)
    {
        $departments = Department::where('tenant_id', tenant()->id)->get();
        $positions   = Position::where('tenant_id', tenant()->id)->get();
        return view('employees.edit', compact('item', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $item)
    {
        $item->update($request->validate([
            'employee_code' => 'required|string|unique:employees,employee_code,' . $item->id,
            'name'          => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'position_id'   => 'nullable|exists:positions,id',
            'phone'         => 'nullable|string',
            'email'         => 'nullable|email',
            'join_date'     => 'nullable|date',
            'salary'        => 'nullable|numeric|min:0',
            'status'        => 'required|in:active,resigned,terminated',
        ]));
        return redirect()->route('employees.index')->with('success', 'Karyawan diperbarui.');
    }

    public function destroy(Employee $item)
    {
        $item->delete();
        return back()->with('success', 'Karyawan dihapus.');
    }
}
