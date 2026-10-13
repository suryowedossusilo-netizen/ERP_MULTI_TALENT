<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::paginate(15);
        return view('tenants.index', compact('tenants'));
    }

    public function create() { return view('tenants.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'domain' => 'nullable|string|unique:tenants,domain',
            'email'  => 'nullable|email',
            'phone'  => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['name']);
        Tenant::create($data);
        return redirect()->route('tenants.index')->with('success', 'Tenant berhasil dibuat.');
    }

    public function edit(Tenant $tenant) { return view('tenants.edit', compact('tenant')); }

    public function update(Request $request, Tenant $tenant)
    {
        $tenant->update($request->validate([
            'name'   => 'required|string|max:255',
            'domain' => 'nullable|string|unique:tenants,domain,' . $tenant->id,
            'email'  => 'nullable|email',
            'phone'  => 'nullable|string',
            'status' => 'required|in:active,suspended,expired',
        ]));
        return redirect()->route('tenants.index')->with('success', 'Tenant berhasil diperbarui.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return back()->with('success', 'Tenant berhasil dihapus.');
    }
}
