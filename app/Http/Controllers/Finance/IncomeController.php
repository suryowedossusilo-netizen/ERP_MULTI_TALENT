<?php
namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    private function owned(Income $item): Income
    {
        abort_unless(tenant() && (int) $item->tenant_id === (int) tenant()->id, 404);
        return $item;
    }

    public function index()
    {
        $items = Income::where('tenant_id', tenant()->id)->paginate(15);
        return view('finance.incomes.index', compact('items'));
    }

    public function create() { return view('finance.incomes.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'income_number' => 'required',
            'amount' => 'required',
        ]);
        $data['tenant_id'] = tenant()->id;
        Income::create($data);
        return redirect()->route('finance.incomes.index')->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    public function show(Income $item)
    {
        $item = $this->owned($item);
        return view('finance.incomes.show', compact('item'));
    }

    public function edit(Income $item)
    {
        $item = $this->owned($item);
        return view('finance.incomes.create', compact('item'));
    }

    public function update(Request $request, Income $item)
    {
        $item = $this->owned($item);
        $item->update($request->validate([
            'income_number' => 'required',
            'amount' => 'required',
        ]));
        return redirect()->route('finance.incomes.index')->with('success', 'Pemasukan berhasil diperbarui.');
    }

    public function destroy(Income $item)
    {
        $item = $this->owned($item);
        $item->delete();
        return back()->with('success', 'Pemasukan berhasil dihapus.');
    }
}
