<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'invoice_number', 'order_id', 'customer_id',
        'issue_date', 'due_date', 'amount', 'paid', 'status'];

    protected $casts = ['issue_date' => 'date', 'due_date' => 'date',
        'amount' => 'decimal:2', 'paid' => 'decimal:2'];

    public function order()    { return $this->belongsTo(Order::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    public function getOutstandingAttribute(): float
    {
        return max(0, (float) $this->amount - (float) $this->paid);
    }
}
