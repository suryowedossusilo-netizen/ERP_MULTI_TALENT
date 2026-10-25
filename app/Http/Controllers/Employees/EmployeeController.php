<?php
namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    private function tenantId(): int
    {
        abort_unless(tenant(), 403, 'Tenant tidak ditemukan.');
        return (int) tenant()->id;
    }

    private function ownedEmployee(Employee $item): Employee
    {
        abort_unless((int) $item->tenant_id === $this->tenantId(), 404);
        return $item;
    }

    private function validateRelations(array $data): array
    {
        $tenantId = $this->tenantId();
        foreach (['department_id' => Department::class, 'position_id' => Position::class] as $field => $model) {
            if (!empty($data[$field])) {
                $belongsToTenant = $model::where('tenant_id', $tenantId)->whereKey($data[$field])->exists();
                abort_unless($belongsToTenant, 422, 'Departemen atau jabatan tidak valid untuk perusahaan ini.');
            }
        }
        return $data;
    }

    private function canManageSalary(): bool
    {
        return auth()->user()->hasPermission('manage-salaries')
            || auth()->user()->hasPermission('manage-payroll');
    }

    public function index()
    {
        $items = Employee::where('tenant_id', $this->tenantId())->with(['department', 'position'])->paginate(15);
        return view('employees.index', compact('items'));
    }

    public function create()
    {
        $departments = Department::where('tenant_id', $this->tenantId())->get();
        $positions = Position::where('tenant_id', $this->tenantId())->get();
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $tenantId = $this->tenantId();
        $data = $request->validate([
            'employee_code' => ['required', 'string', 'max:100', Rule::unique('employees', 'employee_code')->where(fn ($q) => $q->where('tenant_id', $tenantId))],
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|integer',
            'position_id' => 'nullable|integer',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255|required_with:account_password',
            'account_password' => 'nullable|string|min:8',
            'join_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
        ]);
        if (!empty($data['account_password'])) {
            validator($data, [
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            ])->validate();
        }
        $data = $this->validateRelations($data);
        if (!$this->canManageSalary()) {
            unset($data['salary']);
        }

        DB::transaction(function () use ($data, $tenantId) {
            $accountPassword = $data['account_password'] ?? null;
            unset($data['account_password']);
            $data['tenant_id'] = $tenantId;

            if ($accountPassword !== null && $accountPassword !== '') {
                $user = User::create([
                    'tenant_id' => $tenantId,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $accountPassword,
                    'is_active' => true,
                ]);
                $data['user_id'] = $user->id;
            }

            Employee::create($data);
        });

        return redirect()->route('employees.index')->with(
            'success',
            !empty($data['user_id'])
                ? 'Karyawan dan akun login berhasil dibuat.'
                : 'Karyawan ditambahkan. Akun login belum dibuat.'
        );
    }

    public function show(Employee $item)
    {
        $item = $this->ownedEmployee($item);
        return view('employees.show', compact('item'));
    }

    public function edit(Employee $item)
    {
        $item = $this->ownedEmployee($item);
        $departments = Department::where('tenant_id', $this->tenantId())->get();
        $positions = Position::where('tenant_id', $this->tenantId())->get();
        return view('employees.edit', compact('item', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $item)
    {
        $item = $this->ownedEmployee($item);
        $tenantId = $this->tenantId();
        $data = $request->validate([
            'employee_code' => ['required', 'string', 'max:100', Rule::unique('employees', 'employee_code')->where(fn ($q) => $q->where('tenant_id', $tenantId))->ignore($item->id)],
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|integer',
            'position_id' => 'nullable|integer',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'join_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,resigned,terminated',
        ]);
        $data = $this->validateRelations($data);
        if (!$this->canManageSalary()) {
            unset($data['salary']);
        }
        $item->update($data);
        return redirect()->route('employees.index')->with('success', 'Karyawan diperbarui.');
    }

    public function destroy(Employee $item)
    {
        $this->ownedEmployee($item)->delete();
        return back()->with('success', 'Karyawan dihapus.');
    }
}
