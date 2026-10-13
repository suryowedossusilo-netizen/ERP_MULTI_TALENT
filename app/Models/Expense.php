<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'expense_number', 'category', 'amount', 'expense_date', 'description'];

    protected $casts = ['expense_date' => 'date', 'amount' => 'decimal:2'];
}
