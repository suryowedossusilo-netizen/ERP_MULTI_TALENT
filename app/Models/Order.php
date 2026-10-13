<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'order_number', 'customer_id', 'user_id',
        'status', 'subtotal', 'discount', 'tax', 'total', 'note'];

    protected $casts = ['subtotal' => 'decimal:2', 'discount' => 'decimal:2',
        'tax' => 'decimal:2', 'total' => 'decimal:2'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function items()    { return $this->hasMany(OrderItem::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
}
