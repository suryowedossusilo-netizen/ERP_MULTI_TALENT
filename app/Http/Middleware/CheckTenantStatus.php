<?php
namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentTenant = tenant();
        $activeTenant = $currentTenant
            ? Tenant::active()->whereKey($currentTenant->id)->first()
            : null;

        if (!$activeTenant) {
            app()->instance('tenant', null);
            $request->session()->forget(['tenant', 'tenant_id']);

            if ($request->user()) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return redirect()->route('login')->withErrors([
                'tenant' => 'Tenant tidak aktif atau masa langganannya telah berakhir.',
            ]);
        }

        app()->instance('tenant', $activeTenant);
        return $next($request);
    }
}
