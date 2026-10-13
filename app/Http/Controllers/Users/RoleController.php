<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $items = Role::where('tenant_id', tenant()->id)->paginate(15);
        return view('users.roles.index', compact('items'));
    }

    public function create() { return view('users.roles.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Role::create($data);
        return redirect()->route('users.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(Role $item) { return view('users.roles.show', compact('item')); }

    public function edit(Role $item) { return view('users.roles.edit', compact('item')); }

    public function update(Request $request, Role $item)
    {
        $item->update($request->validate([
            'name' => 'required',
        ]));
        return redirect()->route('users.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $item)
    {
        $item->delete();
        return back()->with('success', 'Role berhasil dihapus.');
    }
}
