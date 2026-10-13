<?php
namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Identifikasi tenant dari subdomain (acme.app.test -> acme)
     * atau dari tenant user yang sedang login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($tenant = $request->session()->get('tenant')) {
            app()->instance('tenant', $tenant);
            return $next($request);
        }

        $subdomain = explode('.', $request->getHost())[0];
        if (!in_array($subdomain, ['www', 'app', 'erp']) && $tenant = Tenant::active()->where('slug', $subdomain)->orWhere('domain', $request->getHost())->first()) {
            app()->instance('tenant', $tenant);
            $request->session()->put('tenant', $tenant);
            return $next($request);
        }

        if ($user = $request->user()) {
            app()->instance('tenant', $user->tenant);
            $request->session()->put('tenant', $user->tenant);
            return $next($request);
        }

        return redirect()->route('login')->withErrors(['tenant' => 'Tenant tidak ditemukan.']);
    }
}
