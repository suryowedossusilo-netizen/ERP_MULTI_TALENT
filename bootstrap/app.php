<?php
use Illuminate\\Foundation\\Application;
use Illuminate\\Foundation\\Configuration\\Exceptions;
use Illuminate\\Foundation\\Configuration\\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust the hosting proxy so Laravel detects HTTPS correctly behind TLS termination.
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'tenant'         => \\App\\Http\\Middleware\\IdentifyTenant::class,
            'tenant.status'  => \\App\\Http\\Middleware\\CheckTenantStatus::class,
            'permission'     => \\App\\Http\\Middleware\\CheckPermission::class,
            'password.change.required' => \\App\\Http\\Middleware\\RequirePasswordChange::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// InfinityFree's document root is htdocs. The deployment bundle keeps the
// Laravel application in htdocs/erp_app and places the public files in htdocs.
$sharedHostingPublicPath = dirname(__DIR__, 2);
if (
    basename($sharedHostingPublicPath) === 'htdocs'
    && is_file($sharedHostingPublicPath . DIRECTORY_SEPARATOR . 'index.php')
) {
    $app->usePublicPath($sharedHostingPublicPath);
}

return $app;
