<?php
namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::where('tenant_id', tenant()->id)
            ->with(['product', 'warehouse', 'user'])->latest()->paginate(20);
        return view('inventory.movements', compact('movements'));
    }
}
