<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;

class GeneralSettingsController extends Controller
{
    // Digabung di TenantSettingsController; kelas ini placeholder route
    public function index() { return redirect()->route('settings.company.edit'); }
}
