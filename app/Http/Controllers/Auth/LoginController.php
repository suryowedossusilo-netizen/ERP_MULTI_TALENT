<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
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
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $company = trim($data['company']);
        $tenant = Tenant::active()
            ->where(function ($query) use ($company) {
                $query->where('slug', $company)->orWhere('domain', $company);
            })
            ->first();

        $user = $tenant
            ? User::where('tenant_id', $tenant->id)
                ->where('email', $data['email'])
                ->where('is_active', true)
                ->first()
            : null;

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Identitas perusahaan, email, atau password tidak sesuai.'])
                ->onlyInput('company', 'email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->put('tenant_id', $tenant->id);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
