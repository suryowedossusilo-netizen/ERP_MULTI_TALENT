<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
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
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $company = trim($data['company']);
        $tenant = Tenant::active()->where(function ($query) use ($company) {
            $query->where('slug', $company)->orWhere('domain', $company);
        })->first();

        $reset = $tenant ? DB::table('tenant_password_reset_tokens')
            ->where('tenant_id', $tenant->id)
            ->where('email', $data['email'])
            ->where('token', hash('sha256', $data['token']))
            ->first() : null;

        if (!$reset || !$reset->created_at || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Tautan reset tidak valid atau sudah kedaluwarsa.'])
                ->withInput($request->only('company', 'email', 'token'));
        }

        $user = User::where('tenant_id', $tenant->id)
            ->where('email', $data['email'])->where('is_active', true)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Tautan reset tidak valid atau sudah kedaluwarsa.'])
                ->withInput($request->only('company', 'email', 'token'));
        }

        DB::transaction(function () use ($tenant, $data, $user) {
            $user->forceFill([
                'password' => Hash::make($data['password']),
                'must_change_password' => false,
                'remember_token' => Str::random(60),
            ])->save();
            DB::table('tenant_password_reset_tokens')
                ->where('tenant_id', $tenant->id)->where('email', $data['email'])->delete();
            DB::table('employee_password_reset_logs')->insert([
                'tenant_id' => $tenant->id,
                'actor_user_id' => null,
                'employee_user_id' => $user->id,
                'employee_id' => $user->employee()->value('id'),
                'action' => 'email_reset',
                'created_at' => now(),
            ]);
        });
        event(new PasswordReset($user));

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan masuk kembali.');
    }
}
