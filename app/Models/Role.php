<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name', 'guard_name'];

    public function tenant() { return $this->belongsTo(Tenant::class); }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
            ->where(function ($query) {
                $query->whereNull('permissions.tenant_id');
                if ($this->tenant_id !== null) {
                    $query->orWhere('permissions.tenant_id', $this->tenant_id);
                }
            });
    }

    public function users() { return $this->belongsToMany(User::class, 'user_role'); }
}
