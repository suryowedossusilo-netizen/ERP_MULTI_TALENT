<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->boolean('must_change_password')->default(false);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropUnique('employees_employee_code_unique');
            $table->unique(['tenant_id', 'employee_code'], 'employees_tenant_employee_code_unique');
        });

        Schema::create('tenant_password_reset_tokens', function (Blueprint $table) {
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('token', 64);
            $table->timestamp('created_at')->nullable();
            $table->unique(['tenant_id', 'email'], 'tenant_password_reset_tokens_tenant_email_unique');
        });

        Schema::create('employee_password_reset_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('employee_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('action', 50);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['tenant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_password_reset_logs');
        Schema::dropIfExists('tenant_password_reset_tokens');

        Schema::table('employees', function (Blueprint $table) {
            $table->dropUnique('employees_tenant_employee_code_unique');
            $table->unique('employee_code', 'employees_employee_code_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('must_change_password');
            $table->string('email')->nullable(false)->change();
        });
    }
};
