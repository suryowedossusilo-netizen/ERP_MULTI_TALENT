<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $items = User::where('tenant_id', tenant()->id)->paginate(15);
        return view('users.index', compact('items'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->where(fn ($q) => $q->where('tenant_id', tenant()->id))],
            'password' => 'required|string|min:8',
        ]);

        // Tenant ownership is always assigned server-side.
        $data['tenant_id'] = tenant()->id;
        User::create($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $item)
    {
        $this->ensureCurrentTenant($item);
        return view('users.show', compact('item'));
    }

    public function edit(User $item)
    {
        $this->ensureCurrentTenant($item);
        return view('users.edit', compact('item'));
    }

    public function update(Request $request, User $item)
    {
        $this->ensureCurrentTenant($item);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->where(fn ($q) => $q->where('tenant_id', tenant()->id))->ignore($item->id)],
            'password' => 'nullable|string|min:8',
        ]);

        // A blank password means "keep the existing password".
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        // Never allow tenant_id or account status to be changed through this form.
        unset($data['tenant_id'], $data['is_active']);
        $item->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $item)
    {
        $this->ensureCurrentTenant($item);

        // Prevent an administrator from accidentally deleting their own account.
        abort_if((int) $item->id === (int) auth()->id(), 403, 'Anda tidak dapat menghapus akun sendiri.');

        $item->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    private function ensureCurrentTenant(User $item): void
    {
        abort_unless(
            $item->tenant_id !== null && (int) $item->tenant_id === (int) tenant()->id,
            404
        );
    }
}
