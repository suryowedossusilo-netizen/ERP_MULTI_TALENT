<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteRegistrationTest extends TestCase
{
    public function test_every_registered_controller_action_exists(): void
    {
        foreach (Route::getRoutes() as $route) {
            $action = $route->getActionName();

            if ($action === 'Closure' || !str_contains($action, '@')) {
                continue;
            }

            [$controller, $method] = explode('@', $action, 2);

            $this->assertTrue(
                class_exists($controller) && method_exists($controller, $method),
                sprintf(
                    'Route [%s] points to missing controller action [%s@%s].',
                    $route->uri(),
                    $controller,
                    $method
                )
            );
        }
    }

    public function test_key_resource_route_names_remain_available(): void
    {
        foreach ([
            'users.index',
            'roles.index',
            'employees.index',
            'inventory.index',
            'sales.orders.index',
            'sales.invoices.index',
            'sales.payments.index',
            'purchasing.purchase-orders.index',
            'finance.incomes.index',
            'finance.expenses.index',
            'reports.sales',
            'settings.company.edit',
            'tenants.index',
        ] as $name) {
            $this->assertTrue(
                Route::has($name),
                sprintf('Expected route name [%s] to be registered.', $name)
            );
        }
    }
}
