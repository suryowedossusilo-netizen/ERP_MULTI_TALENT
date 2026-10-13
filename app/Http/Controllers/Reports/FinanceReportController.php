<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Expense;

class FinanceReportController extends Controller
{
    public function index()
    {
        $t = tenant()->id;
        $byMonthIncome  = Income::where('tenant_id', $t)->selectRaw("strftime('%Y-%m', income_date) as m, SUM(amount) as total")->groupBy('m')->pluck('total', 'm');
        $byMonthExpense = Expense::where('tenant_id', $t)->selectRaw("strftime('%Y-%m', expense_date) as m, SUM(amount) as total")->groupBy('m')->pluck('total', 'm');
        return view('reports.finance', compact('byMonthIncome', 'byMonthExpense'));
    }
}
