<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->date('start') ?? now()->startOfMonth();
        $end   = $request->date('end') ?? now()->endOfMonth();
        $orders = Order::where('tenant_id', tenant()->id)
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')->get();
        return view('reports.sales', compact('orders', 'start', 'end'));
    }
}
