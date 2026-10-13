<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'company_name', 'logo', 'address',
        'phone', 'email', 'tax_number', 'theme'];

    protected $casts = ['theme' => 'array'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
