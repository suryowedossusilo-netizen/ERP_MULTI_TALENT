<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('join_date')->nullable();
            $table->decimal('salary', 15, 2)->default(0);
            $table->enum('status', ['active', 'resigned', 'terminated'])->default('active');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('employees'); }
};
