<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\Request;

class TenantSettingsController extends Controller
{
    public function edit()
    {
        $setting = CompanySetting::firstOrCreate(
            ['tenant_id' => tenant()->id],
            ['company_name' => tenant()->name]
        );
        return view('settings.general', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'address'      => 'nullable|string',
            'phone'        => 'nullable|string',
            'email'        => 'nullable|email',
            'tax_number'   => 'nullable|string',
        ]);
        CompanySetting::updateOrCreate(['tenant_id' => tenant()->id], $data);
        return back()->with('success', 'Pengaturan perusahaan disimpan.');
    }
}
