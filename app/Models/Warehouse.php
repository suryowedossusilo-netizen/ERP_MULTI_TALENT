<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'code', 'name', 'address'];

    public function stocks() { return $this->hasMany(Stock::class); }
}
