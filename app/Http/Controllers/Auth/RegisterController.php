<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm() { return view('auth.register'); }

    public function register(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8|confirmed',
        ]);

        $tenant = Tenant::create([
            'name'   => $data['company_name'],
            'slug'   => Str::slug($data['company_name']) . '-' . Str::random(6),
            'status' => 'active',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
