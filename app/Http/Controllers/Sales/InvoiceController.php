<?php
namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Services\NumberingService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('tenant_id', tenant()->id)->with('customer')->latest()->paginate(15);
        return view('sales.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $orders = Order::where('tenant_id', tenant()->id)->whereDoesntHave('invoices')->get();
        return view('sales.invoices.create', compact('orders'));
    }

    public function store(Request $request, NumberingService $numbering)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
            'due_date' => 'nullable|date',
        ]);

        $order = Order::where('tenant_id', tenant()->id)->findOrFail($data['order_id']);
        if ($order->status === 'draft' || $order->status === 'cancelled') {
            return back()->withErrors(['order_id' => 'Invoice hanya dapat dibuat untuk order yang sudah dikonfirmasi.'])->withInput();
        }
        if ($order->invoices()->exists()) {
            return back()->withErrors(['order_id' => 'Order ini sudah memiliki invoice.'])->withInput();
        }

        $invoice = Invoice::create([
            'tenant_id'      => tenant()->id,
            'invoice_number' => $numbering->next(tenant()->id, 'invoice', 'INV'),
            'order_id'       => $order->id,
            'customer_id'    => $order->customer_id,
            'issue_date'     => now(),
            'due_date'       => $data['due_date'] ?? null,
            'amount'         => $order->total,
            'paid'           => 0,
            'status'         => 'unpaid',
        ]);
        return redirect()->route('sales.invoices.show', $invoice)->with('success', 'Invoice dibuat.');
    }

    public function show(Invoice $invoice)
    {
        $invoice = Invoice::where('tenant_id', tenant()->id)->whereKey($invoice->id)->firstOrFail();
        $invoice->load('order.items', 'payments');
        return view('sales.invoices.show', compact('invoice'));
    }
}
