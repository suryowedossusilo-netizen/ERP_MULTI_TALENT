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
                'required', 'string', 'max:255',
                Rule::unique('roles', 'name')->where('tenant_id', tenant()->id),
            ],
        ]);

        // Tenant ownership is assigned by the server, never by request input.
        $data['tenant_id'] = tenant()->id;
        $data['guard_name'] = 'web';
        Role::create($data);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(Role $item)
    {
        $this->ensureCurrentTenant($item);
        return view('users.roles.show', compact('item'));
    }

    public function edit(Role $item)
    {
        $this->ensureCurrentTenant($item);
        return view('users.roles.edit', compact('item'));
    }

    public function update(Request $request, Role $item)
    {
        $this->ensureCurrentTenant($item);

        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('roles', 'name')
                    ->where('tenant_id', tenant()->id)
                    ->ignore($item->id),
            ],
        ]);

        // Prevent moving a role to another tenant or changing its guard.
        $item->update(['name' => $data['name']]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $item)
    {
        $this->ensureCurrentTenant($item);

        // Do not remove a role while users still depend on it; force reassignment first.
        abort_if($item->users()->exists(), 409, 'Role masih digunakan pengguna. Lepaskan role dari pengguna terlebih dahulu.');

        $item->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    private function ensureCurrentTenant(Role $item): void
    {
        abort_unless(
            $item->tenant_id !== null && (int) $item->tenant_id === (int) tenant()->id,
            404
        );
    }
}
