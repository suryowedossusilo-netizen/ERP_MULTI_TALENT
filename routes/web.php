<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', fn () => redirect()->route('login'));

require __DIR__ . '/auth.php';

Route::middleware(['tenant', 'tenant.status', 'auth', 'password.change.required'])->group(function () {
    Route::get('/password/force-change', [\\App\\Http\\Controllers\\Auth\\ForcePasswordChangeController::class, 'edit'])->name('password.force-change');
    Route::put('/password/force-change', [\\App\\Http\\Controllers\\Auth\\ForcePasswordChangeController::class, 'update'])->name('password.force-change.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User and role administration
    Route::middleware('permission:manage-users')->group(function () {
        Route::resource('users', \App\Http\Controllers\Users\UserController::class)
            ->parameters(['users' => 'item']);
    });

    Route::middleware('permission:manage-roles')->group(function () {
        Route::resource('roles', \App\Http\Controllers\Users\RoleController::class)
            ->parameters(['roles' => 'item']);
    });

    // Employees
    Route::middleware('permission:manage-employees')->group(function () {
        Route::resource('employees', \App\Http\Controllers\Employees\EmployeeController::class)
            ->parameters(['employees' => 'item']);
        Route::resource('departments', \App\Http\Controllers\Employees\DepartmentController::class)
            ->parameters(['departments' => 'item']);
        Route::resource('positions', \App\Http\Controllers\Employees\PositionController::class)
            ->parameters(['positions' => 'item']);
    });

    // CRM
    Route::middleware('permission:manage-sales')->group(function () {
        Route::resource('customers', \App\Http\Controllers\Customers\CustomerController::class)
            ->parameters(['customers' => 'item']);
    });
    Route::middleware('permission:manage-purchasing')->group(function () {
        Route::resource('suppliers', \App\Http\Controllers\Suppliers\SupplierController::class)
            ->parameters(['suppliers' => 'item']);
    });

    // Products
    Route::middleware('permission:manage-products')->group(function () {
        Route::resource('products', \App\Http\Controllers\Products\ProductController::class)
            ->parameters(['products' => 'item']);
        Route::resource('categories', \App\Http\Controllers\Products\CategoryController::class)
            ->parameters(['categories' => 'item']);
        Route::resource('units', \App\Http\Controllers\Products\UnitController::class)
            ->parameters(['units' => 'item']);
    });

    // Inventory
    Route::middleware('permission:manage-inventory')->group(function () {
        Route::resource('warehouses', \App\Http\Controllers\Inventory\WarehouseController::class)
            ->parameters(['warehouses' => 'item']);
        Route::get('inventory', [\App\Http\Controllers\Inventory\StockController::class, 'index'])->name('inventory.index');
        Route::get('inventory/adjust', [\App\Http\Controllers\Inventory\StockController::class, 'create'])->name('inventory.adjust');
        Route::post('inventory/adjust', [\App\Http\Controllers\Inventory\StockController::class, 'store'])->name('inventory.adjust.store');
        Route::get('inventory/movements', [\App\Http\Controllers\Inventory\StockMovementController::class, 'index'])->name('inventory.movements');
    });

    // Sales
    Route::middleware('permission:manage-sales')->prefix('sales')->name('sales.')->group(function () {
        Route::resource('orders', \App\Http\Controllers\Sales\OrderController::class)
            ->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::post('orders/{order}/confirm', [\App\Http\Controllers\Sales\OrderController::class, 'confirm'])->name('orders.confirm');
        Route::resource('invoices', \App\Http\Controllers\Sales\InvoiceController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::resource('payments', \App\Http\Controllers\Sales\PaymentController::class)
            ->only(['index', 'create', 'store']);
    });

    // Purchasing
    Route::middleware('permission:manage-purchasing')->prefix('purchasing')->name('purchasing.')->group(function () {
        Route::resource('purchase-orders', \App\Http\Controllers\Purchasing\PurchaseOrderController::class)
            ->parameters(['purchase-orders' => 'item']);
        Route::post('purchase-orders/{purchaseOrder}/receipt', [\App\Http\Controllers\Purchasing\PurchaseReceiptController::class, 'store'])->name('receipts.store');
    });

    // Finance
    Route::middleware('permission:manage-finance')->prefix('finance')->name('finance.')->group(function () {
        Route::resource('incomes', \App\Http\Controllers\Finance\IncomeController::class)
            ->parameters(['incomes' => 'item']);
        Route::resource('expenses', \App\Http\Controllers\Finance\ExpenseController::class)
            ->parameters(['expenses' => 'item']);
        Route::get('cash-flow', [\App\Http\Controllers\Finance\CashFlowController::class, 'index'])->name('cashflow');
    });

    // Reports
    Route::middleware('permission:view-reports')->prefix('reports')->name('reports.')->group(function () {
        Route::get('sales', [\App\Http\Controllers\Reports\SalesReportController::class, 'index'])->name('sales');
        Route::get('inventory', [\App\Http\Controllers\Reports\InventoryReportController::class, 'index'])->name('inventory');
        Route::get('finance', [\App\Http\Controllers\Reports\FinanceReportController::class, 'index'])->name('finance');
    });

    // Settings
    Route::middleware('permission:manage-settings')->prefix('settings')->name('settings.')->group(function () {
        Route::get('company', [\App\Http\Controllers\Tenant\TenantSettingsController::class, 'edit'])->name('company.edit');
        Route::put('company', [\App\Http\Controllers\Tenant\TenantSettingsController::class, 'update'])->name('company.update');
        Route::get('numbering', [\App\Http\Controllers\Settings\NumberingController::class, 'edit'])->name('numbering.edit');
        Route::put('numbering', [\App\Http\Controllers\Settings\NumberingController::class, 'update'])->name('numbering.update');
        Route::get('appearance', [\App\Http\Controllers\Settings\AppearanceController::class, 'edit'])->name('appearance.edit');
        Route::put('appearance', [\App\Http\Controllers\Settings\AppearanceController::class, 'update'])->name('appearance.update');
    });

    // Super-admin only
    Route::middleware('permission:manage-tenants')->group(function () {
        Route::resource('tenants', \App\Http\Controllers\Tenant\TenantController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });
});
