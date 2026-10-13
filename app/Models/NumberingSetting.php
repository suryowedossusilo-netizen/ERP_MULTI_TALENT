<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NumberingSetting extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'module', 'prefix', 'next_number', 'padding'];
}
