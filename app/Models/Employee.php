<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'user_id', 'department_id', 'position_id',
        'employee_code', 'name', 'phone', 'email', 'join_date', 'salary', 'status'];

    protected $casts = ['join_date' => 'date', 'salary' => 'decimal:2'];

    public function tenant()     { return $this->belongsTo(Tenant::class); }
    public function user()       { return $this->belongsTo(User::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function position()   { return $this->belongsTo(Position::class); }
}
