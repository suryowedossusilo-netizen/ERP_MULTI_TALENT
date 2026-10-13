<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'income_number', 'category', 'amount', 'income_date', 'description'];

    protected $casts = ['income_date' => 'date', 'amount' => 'decimal:2'];
}
