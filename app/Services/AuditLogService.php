<?php
namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public function log(string $action, ?Model $model = null, ?array $old = null, ?array $new = null): AuditLog
    {
        return AuditLog::create([
            'tenant_id'   => tenant()?->id,
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model?->id,
            'old_values'  => $old,
            'new_values'  => $new,
            'ip_address'  => request()->ip(),
        ]);
    }
}
