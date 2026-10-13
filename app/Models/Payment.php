<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'payment_number', 'invoice_id', 'type',
        'amount', 'method', 'payment_date', 'note'];

    protected $casts = ['payment_date' => 'date', 'amount' => 'decimal:2'];

    public function invoice() { return $this->belongsTo(Invoice::class); }
}
