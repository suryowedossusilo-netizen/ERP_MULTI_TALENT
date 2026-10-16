<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = DB::transaction(function () use ($data) {
            $baseSlug = Str::slug($data['company_name']) ?: 'company';
            $slug = $baseSlug;
            while (Tenant::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . Str::lower(Str::random(6));
            }

            $tenant = Tenant::create([
                'name' => $data['company_name'],
                'slug' => $slug,
                'status' => 'active',
            ]);

            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'is_active' => true,
            ]);

            // A tenant administrator is tenant-owned and does not receive system-wide tenant-management rights.
            $role = Role::firstOrCreate([
                'tenant_id' => $tenant->id,
                'name' => 'admin',
            ], ['guard_name' => 'web']);

            $permissionNames = [
                'manage-users', 'manage-roles', 'manage-products', 'manage-inventory',
                'manage-sales', 'manage-purchasing', 'manage-finance', 'view-reports',
                'manage-settings', 'manage-employees', 'manage-salaries', 'manage-payroll',
            ];
            $permissionIds = [];
            foreach ($permissionNames as $name) {
                $permission = Permission::firstOrCreate([
                    'tenant_id' => null,
                    'name' => $name,
                ], ['guard_name' => 'web']);
                $permissionIds[] = $permission->id;
            }
            $role->permissions()->syncWithoutDetaching($permissionIds);
            $user->roles()->syncWithoutDetaching([$role->id]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('tenant_id', $user->tenant_id);

        return redirect()->route('dashboard');
    }
}
