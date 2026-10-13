<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $items = User::where('tenant_id', tenant()->id)->paginate(15);
        return view('users.index', compact('items'));
    }

    public function create() { return view('users.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        User::create($data);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $item) { return view('users.show', compact('item')); }

    public function edit(User $item) { return view('users.edit', compact('item')); }

    public function update(Request $request, User $item)
    {
        $item->update($request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]));
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $item)
    {
        $item->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
