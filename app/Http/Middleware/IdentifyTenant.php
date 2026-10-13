<?php
namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Resolve tenant from the authenticated user's current database record,
     * or from an active tenant's configured host for unauthenticated requests.
     * Never trust a serialized tenant object stored in the session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Avoid stale tenant state in long-lived workers.
        app()->forgetInstance('tenant');

        if ($user = $request->user()) {
            $tenant = Tenant::active()->whereKey($user->tenant_id)->first();

            if (!$tenant) {
                $request->session()->forget('tenant');
                app()->instance('tenant', null);
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'tenant' => 'Tenant tidak aktif atau masa langganannya telah berakhir.',
                ]);
            }

            $request->session()->forget('tenant');
            $request->session()->put('tenant_id', $tenant->id);
            app()->instance('tenant', $tenant);
            return $next($request);
        }

        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        if (!in_array($subdomain, ['www', 'app', 'erp'], true)) {
            $tenant = Tenant::active()
                ->where(function ($query) use ($subdomain, $host) {
                    $query->where('slug', $subdomain)
                        ->orWhere('domain', $host);
                })
                ->first();

            if ($tenant) {
                app()->instance('tenant', $tenant);
                $request->session()->put('tenant_id', $tenant->id);
                return $next($request);
            }
        }

        app()->instance('tenant', null);
        return redirect()->route('login')->withErrors([
            'tenant' => 'Tenant tidak ditemukan atau tidak aktif.',
        ]);
    }
}
