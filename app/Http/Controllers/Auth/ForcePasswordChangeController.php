<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForcePasswordChangeController extends Controller
{
    public function edit()
    {
        return view('auth.force-password-change');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->forceFill([
            'password' => Hash::make($data['password']),
            'must_change_password' => false,
            'remember_token' => null,
        ])->save();

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Password berhasil diganti.');
    }
}
