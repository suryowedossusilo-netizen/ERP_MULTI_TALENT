<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        $t = tenant()->id;
        $stats = [
            'products'    => Product::where('tenant_id', $t)->count(),
            'customers'   => Customer::where('tenant_id', $t)->count(),
            'orders'      => Order::where('tenant_id', $t)->count(),
            'revenue'     => Invoice::where('tenant_id', $t)->where('status', 'paid')->sum('amount'),
            'outstanding' => Invoice::where('tenant_id', $t)->whereIn('status', ['unpaid', 'partial'])->sum('amount'),
        ];
        $recentOrders = Order::where('tenant_id', $t)->latest()->take(5)->get();
        return view('dashboard.index', compact('stats', 'recentOrders'));
    }
}
