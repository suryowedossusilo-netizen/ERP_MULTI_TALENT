<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['tenant_id', 'name', 'email', 'password', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed', 'is_active' => 'boolean'];
    }

    public function tenant()     { return $this->belongsTo(Tenant::class); }
    public function roles()      { return $this->belongsToMany(Role::class, 'user_role'); }
    public function employee()   { return $this->hasOne(Employee::class); }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()->whereHas('permissions', fn ($q) => $q->where('name', $permission))->exists();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }
}
