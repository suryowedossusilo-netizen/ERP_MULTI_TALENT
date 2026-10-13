<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(private NumberingService $numbering) {}

    public function record(int $tenantId, array $data): Payment
    {
        return DB::transaction(function () use ($tenantId, $data) {
            $invoice = Invoice::lockForUpdate()->findOrFail($data['invoice_id']);
            $payment = Payment::create([
                'tenant_id'      => $tenantId,
                'payment_number' => $this->numbering->next($tenantId, 'payment', 'PAY'),
                'invoice_id'     => $invoice->id,
                'type'           => 'in',
                'amount'         => $data['amount'],
                'method'         => $data['method'],
                'payment_date'   => $data['payment_date'],
                'note'           => $data['note'] ?? null,
            ]);
            $paid = $invoice->paid + $data['amount'];
            $invoice->update([
                'paid'   => min($paid, $invoice->amount),
                'status' => $paid >= $invoice->amount ? 'paid' : 'partial',
            ]);
            return $payment;
        });
    }
}
