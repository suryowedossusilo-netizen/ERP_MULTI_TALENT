<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'domain', 'email', 'phone', 'status', 'subscription_ends_at'];

    protected $casts = ['subscription_ends_at' => 'date'];

    public function users()        { return $this->hasMany(User::class); }
    public function employees()    { return $this->hasMany(Employee::class); }
    public function customers()    { return $this->hasMany(Customer::class); }
    public function suppliers()    { return $this->hasMany(Supplier::class); }
    public function products()     { return $this->hasMany(Product::class); }
    public function warehouses()   { return $this->hasMany(Warehouse::class); }
    public function orders()       { return $this->hasMany(Order::class); }
    public function setting()      { return $this->hasOne(CompanySetting::class); }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('subscription_ends_at')->orWhere('subscription_ends_at', '>=', now()));
    }
}
