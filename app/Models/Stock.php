<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'product_id', 'warehouse_id', 'quantity'];

    public function product()   { return $this->belongsTo(Product::class); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
}
