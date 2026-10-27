<?php
namespace App\Http\Controllers\Employees;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class EmployeePasswordController extends Controller
{
    public function reset(Request $request, Employee $item)
    {
        $tenantId = (int) tenant()->id;
        abort_unless((int) $item->tenant_id === $tenantId, 404);
        abort_unless((int) $request->user()->tenant_id === $tenantId, 403);
        abort_unless($request->user()->hasPermission('reset-employee-password'), 403);
        $item->load('user');
        $employeeUser = $item->user;
        abort_unless($employeeUser && (int) $employeeUser->tenant_id === $tenantId, 422, 'Karyawan belum memiliki akun login.');
        $temporaryPassword = Str::random(16);
        DB::transaction(function () use ($item, $employeeUser, $tenantId, $temporaryPassword, $request) {
            $employeeUser->forceFill([
                'password' => $temporaryPassword,
                'must_change_password' => true,
                'remember_token' => Str::random(60),
            ])->save();
            DB::table('employee_password_reset_logs')->insert([
                'tenant_id' => $tenantId,
                'actor_user_id' => $request->user()->id,
                'employee_user_id' => $employeeUser->id,
                'employee_id' => $item->id,
                'action' => 'admin_reset',
                'created_at' => now(),
            ]);
        });
        return back()->with('temporary_password', $temporaryPassword)
            ->with('success', 'Password sementara dibuat. Berikan langsung kepada karyawan setelah verifikasi identitas.');
    }
}
