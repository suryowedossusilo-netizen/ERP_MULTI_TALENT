<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\TenantPasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'email' => 'required|email|max:255',
        ]);

        $company = trim($data['company']);
        $tenant = Tenant::active()
            ->where(function ($query) use ($company) {
                $query->where('slug', $company)->orWhere('domain', $company);
            })->first();

        $user = $tenant
            ? User::where('tenant_id', $tenant->id)
                ->where('email', $data['email'])
                ->where('is_active', true)
                ->first()
            : null;

        // Keep the response generic so this form does not reveal account existence.
        if ($user) {
            $token = Str::random(64);
            DB::table('tenant_password_reset_tokens')->updateOrInsert(
                ['tenant_id' => $tenant->id, 'email' => $user->email],
                ['token' => hash('sha256', $token), 'created_at' => now()]
            );

            $user->notify(new TenantPasswordResetNotification($token, $tenant->slug, $user->email));
        }

        return back()->with('success', 'Jika akun aktif dengan email tersebut tersedia di perusahaan itu, tautan pemulihan akan dikirim.');
    }
}
