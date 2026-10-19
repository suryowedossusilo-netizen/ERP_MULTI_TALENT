<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace global email uniqueness with tenant-scoped uniqueness.
     *
     * This migration is prepared for review only and has not been run.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
            $table->unique(
                ['tenant_id', 'email'],
                'users_tenant_id_email_unique'
            );
        });
    }

    /**
     * Restore global email uniqueness.
     *
     * This rollback may fail if the same email exists in multiple tenants
     * after the migration has been applied.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_tenant_id_email_unique');
            $table->unique('email', 'users_email_unique');
        });
    }
};
