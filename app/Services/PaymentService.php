<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(private NumberingService $numbering) {}

    public function record(int $tenantId, array $data): Payment
    {
        return DB::transaction(function () use ($tenantId, $data) {
            // Scope the lookup before locking so one tenant cannot pay another tenant's invoice.
            $invoice = Invoice::where('tenant_id', $tenantId)
                ->lockForUpdate()
                ->findOrFail($data['invoice_id']);

            $amount = round((float) $data['amount'], 2);
            $outstanding = round((float) $invoice->amount - (float) $invoice->paid, 2);

            if ($amount <= 0 || $amount > $outstanding || in_array($invoice->status, ['paid'], true)) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal pembayaran harus lebih dari 0 dan tidak boleh melebihi sisa tagihan.',
                ]);
            }

            $payment = Payment::create([
                'tenant_id'      => $tenantId,
                'payment_number' => $this->numbering->next($tenantId, 'payment', 'PAY'),
                'invoice_id'     => $invoice->id,
                'type'           => 'in',
                'amount'         => $amount,
                'method'         => $data['method'],
                'payment_date'   => $data['payment_date'],
                'note'           => $data['note'] ?? null,
            ]);

            $paid = round((float) $invoice->paid + $amount, 2);
            $invoice->update([
                'paid'   => $paid,
                'status' => $paid >= (float) $invoice->amount ? 'paid' : 'partial',
            ]);

            return $payment;
        });
    }
}
