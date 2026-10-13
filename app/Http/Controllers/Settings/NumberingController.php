<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\NumberingSetting;
use Illuminate\Http\Request;

class NumberingController extends Controller
{
    public function edit()
    {
        $settings = NumberingSetting::where('tenant_id', tenant()->id)->get()->keyBy('module');
        return view('settings.numbering', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->input('modules', []) as $module => $cfg) {
            NumberingSetting::updateOrCreate(
                ['tenant_id' => tenant()->id, 'module' => $module],
                ['prefix' => $cfg['prefix'] ?? '', 'next_number' => $cfg['next_number'] ?? 1]
            );
        }
        return back()->with('success', 'Penomoran disimpan.');
    }
}
