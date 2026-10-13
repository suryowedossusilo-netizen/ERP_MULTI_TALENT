<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Stock;

class InventoryReportController extends Controller
{
    public function index()
    {
        $stocks = Stock::where('tenant_id', tenant()->id)->with(['product.category', 'warehouse'])->get();
        $totalValue = $stocks->sum(fn ($s) => $s->quantity * $s->product->purchase_price);
        return view('reports.inventory', compact('stocks', 'totalValue'));
    }
}
