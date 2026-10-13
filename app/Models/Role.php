<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name', 'guard_name'];

    public function tenant()       { return $this->belongsTo(Tenant::class); }
    public function permissions()  { return $this->belongsToMany(Permission::class, 'role_permission'); }
    public function users()        { return $this->belongsToMany(User::class, 'user_role'); }
}
