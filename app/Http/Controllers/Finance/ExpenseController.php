<?php
namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $view = 'finance.expenses.index';
        $items = Expense::where('tenant_id', tenant()->id)->paginate(15);

        if (!view()->exists($view)) {
            return redirect()->route('finance.expenses.index');
        }

        return view($view, compact('items'));
    }

    public function create()
    {
        $view = 'finance.expenses.create';

        if (!view()->exists($view)) {
            return redirect()->route('finance.expenses.index');
        }

        return view($view);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_number' => 'required',
            'amount' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Expense::create($data);
        return redirect()->route('finance.expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function show(Expense $item)
    {
        $view = 'finance.expenses.show';

        if (!view()->exists($view)) {
            return redirect()->route('finance.expenses.index');
        }

        return view($view, compact('item'));
    }

    public function edit(Expense $item)
    {
        $view = 'finance.expenses.edit';

        if (!view()->exists($view)) {
            return redirect()->route('finance.expenses.index');
        }

        return view($view, compact('item'));
    }

    public function update(Request $request, Expense $item)
    {
        $item->update($request->validate([
            'expense_number' => 'required',
            'amount' => 'required',
        ]));
        return redirect()->route('finance.expenses.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $item)
    {
        $item->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
