<?php
namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index()
    {
        $payments = \App\Models\Payment::where('tenant_id', tenant()->id)->with('invoice')->latest()->paginate(15);
        return view('sales.payments.index', compact('payments'));
    }

    public function create()
    {
        $invoices = Invoice::where('tenant_id', tenant()->id)->whereIn('status', ['unpaid', 'partial'])->get();
        return view('sales.payments.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id'   => 'required|integer',
            'amount'       => 'required|numeric|min:1',
            'method'       => 'required|string',
            'payment_date' => 'required|date',
            'note'         => 'nullable|string',
        ]);
        $payment = $this->paymentService->record(tenant()->id, $data);
        return redirect()->route('sales.payments.index')->with('success', 'Pembayaran ' . $payment->payment_number . ' tercatat.');
    }
}
