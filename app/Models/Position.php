<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name'];

    public function employees() { return $this->hasMany(Employee::class); }
}
