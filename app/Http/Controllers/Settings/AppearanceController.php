<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    public function edit()
    {
        $setting = CompanySetting::firstOrNew(['tenant_id' => tenant()->id]);
        return view('settings.appearance', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'theme.primary_color' => 'nullable|string',
            'theme.sidebar'       => 'nullable|in:dark,light',
        ]);
        CompanySetting::updateOrCreate(['tenant_id' => tenant()->id], ['theme' => $data['theme'] ?? []]);
        return back()->with('success', 'Tampilan disimpan.');
    }
}
