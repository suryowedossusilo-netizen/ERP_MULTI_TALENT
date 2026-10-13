<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $items = Role::where('tenant_id', tenant()->id)->paginate(15);
        return view('users.roles.index', compact('items'));
    }

    public function create()
    {
        return view('users.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('roles', 'name')->where(fn ($query) => $query->where('tenant_id', tenant()->id)),
            ],
        ]);

        Role::create(['tenant_id' => tenant()->id, 'name' => $data['name'], 'guard_name' => 'web']);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(string $role)
    {
        $item = $this->findTenantRole($role);
        return view('users.roles.show', compact('item'));
    }

    public function edit(string $role)
    {
        $item = $this->findTenantRole($role);
        return view('users.roles.edit', compact('item'));
    }

    public function update(Request $request, string $role)
    {
        $item = $this->findTenantRole($role);
        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('roles', 'name')
                    ->where(fn ($query) => $query->where('tenant_id', tenant()->id))
                    ->ignore($item->id),
            ],
        ]);

        $item->update(['name' => $data['name']]);
        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(string $role)
    {
        $this->findTenantRole($role)->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    private function findTenantRole(string $id): Role
    {
        return Role::where('tenant_id', tenant()->id)->findOrFail($id);
    }
}
