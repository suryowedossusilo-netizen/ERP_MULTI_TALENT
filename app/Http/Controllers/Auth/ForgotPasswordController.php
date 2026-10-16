<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $data = $request->validate([
            'company' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $company = trim($data['company']);
        $tenant = Tenant::active()
            ->where(function ($query) use ($company) {
                $query->where('slug', $company)->orWhere('domain', $company);
            })->first();

        $userExists = $tenant && User::where('tenant_id', $tenant->id)
            ->where('email', $data['email'])
            ->where('is_active', true)
            ->exists();

        if (!$userExists) {
            return back()->withErrors([
                'email' => 'Perusahaan atau akun tidak ditemukan, atau akun tidak aktif.',
            ])->onlyInput('company', 'email');
        }

        // Current schema has globally unique user emails. Tenant-aware token storage
        // must be added together with the separately approved per-tenant email migration.
        $status = Password::sendResetLink(['email' => $data['email']]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)])->onlyInput('company', 'email');
    }
}
