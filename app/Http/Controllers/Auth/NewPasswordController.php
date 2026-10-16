<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
            'company' => $request->query('company', ''),
        ]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'company' => 'required|string|max:255',
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $company = trim($data['company']);
        $tenant = Tenant::active()
            ->where(function ($query) use ($company) {
                $query->where('slug', $company)->orWhere('domain', $company);
            })->first();

        $belongsToTenant = $tenant && User::where('tenant_id', $tenant->id)
            ->where('email', $data['email'])
            ->where('is_active', true)
            ->exists();

        if (!$belongsToTenant) {
            return back()->withErrors([
                'email' => 'Identitas perusahaan atau akun tidak valid.',
            ])->withInput($request->only('company', 'email', 'token'));
        }

        // The current users.email column is globally unique. A tenant-aware reset-token
        // store is required before allowing duplicate emails across tenants.
        $status = Password::reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token'],
            ],
            function (User $user) use ($data) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)])
                ->withInput($request->only('company', 'email', 'token'));
    }
}
