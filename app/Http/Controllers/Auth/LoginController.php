<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'company' => 'required|string|max:255',
            'employee_code' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        $company = trim($data['company']);
        $tenant = Tenant::active()
            ->where(function ($query) use ($company) {
                $query->where('slug', $company)->orWhere('domain', $company);
            })
            ->first();

        $employee = $tenant
            ? Employee::where('tenant_id', $tenant->id)
                ->where('employee_code', trim($data['employee_code']))
                ->where('name', trim($data['name']))
                ->where('status', 'active')
                ->with('user')
                ->first()
            : null;

        $user = $employee?->user;

        if (
            !$user ||
            (int) $user->tenant_id !== (int) $tenant->id ||
            !$user->is_active ||
            !Hash::check($data['password'], $user->password)
        ) {
            return back()
                ->withErrors(['employee_code' => 'Identitas perusahaan atau data login karyawan tidak sesuai.'])
                ->onlyInput('company', 'employee_code', 'name');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->put('tenant_id', $tenant->id);

        return $user->must_change_password
            ? redirect()->route('password.force-change')
            : redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
