<?php
namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Expense;

class CashFlowController extends Controller
{
    public function index()
    {
        $t = tenant()->id;
        $incomes  = Income::where('tenant_id', $t)->orderBy('income_date')->get();
        $expenses = Expense::where('tenant_id', $t)->orderBy('expense_date')->get();
        $totalIn  = $incomes->sum('amount');
        $totalOut = $expenses->sum('amount');
        return view('finance.cashflow', compact('incomes', 'expenses', 'totalIn', 'totalOut'));
    }
}
