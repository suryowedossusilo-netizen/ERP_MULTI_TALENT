<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'code', 'name', 'email', 'phone', 'address', 'credit_limit'];

    protected $casts = ['credit_limit' => 'decimal:2'];

    public function orders()   { return $this->hasMany(Order::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
}
