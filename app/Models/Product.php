<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'category_id', 'unit_id', 'sku', 'barcode',
        'name', 'description', 'purchase_price', 'selling_price', 'min_stock', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function category()  { return $this->belongsTo(Category::class); }
    public function unit()      { return $this->belongsTo(Unit::class); }
    public function stocks()    { return $this->hasMany(Stock::class); }
    public function orderItems(){ return $this->hasMany(OrderItem::class); }

    public function getTotalStockAttribute(): float
    {
        return (float) $this->stocks()->sum('quantity');
    }
}
