<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'po_number', 'supplier_id', 'status',
        'subtotal', 'tax', 'total', 'order_date', 'note'];

    protected $casts = ['order_date' => 'date', 'subtotal' => 'decimal:2',
        'tax' => 'decimal:2', 'total' => 'decimal:2'];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function items()    { return $this->hasMany(PurchaseOrderItem::class); }
}
