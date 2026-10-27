<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['tenant_id', 'name', 'email', 'password', 'is_active', 'must_change_password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed', 'is_active' => 'boolean', 'must_change_password' => 'boolean'];
    }

    public function tenant()   { return $this->belongsTo(Tenant::class); }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')
            ->where('roles.tenant_id', $this->tenant_id);
    }

    public function employee() { return $this->hasOne(Employee::class); }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('permissions.name', $permission)
                    ->where(function ($scope) {
                        $scope->whereNull('permissions.tenant_id')
                            ->orWhere('permissions.tenant_id', $this->tenant_id);
                    });
            })->exists();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('roles.name', $role)->exists();
    }
}
