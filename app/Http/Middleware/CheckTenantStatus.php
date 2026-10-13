<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();
        if (!$tenant || $tenant->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['tenant' => 'Langganan tenant tidak aktif.']);
        }
        return $next($request);
    }
}
