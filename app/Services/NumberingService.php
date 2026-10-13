<?php
namespace App\Services;

use App\Models\NumberingSetting;
use Illuminate\Support\Facades\DB;

class NumberingService
{
    public function next(int $tenantId, string $module, ?string $defaultPrefix = null): string
    {
        return DB::transaction(function () use ($tenantId, $module, $defaultPrefix) {
            $setting = NumberingSetting::lockForUpdate()->firstOrCreate(
                ['tenant_id' => $tenantId, 'module' => $module],
                ['prefix' => $defaultPrefix ?? strtoupper($module), 'next_number' => 1, 'padding' => 5]
            );
            $number = $setting->prefix . str_pad($setting->next_number, $setting->padding, '0', STR_PAD_LEFT);
            $setting->increment('next_number');
            return $number;
        });
    }
}
