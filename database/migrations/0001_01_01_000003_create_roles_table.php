<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            // Null tenant_id is reserved for system-wide roles managed by seeders.
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->timestamps();
            $table->unique(['tenant_id', 'name']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('roles');
    }
};
