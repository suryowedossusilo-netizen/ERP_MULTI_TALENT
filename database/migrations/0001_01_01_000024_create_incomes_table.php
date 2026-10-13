<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('income_number')->unique();
            $table->string('category')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('income_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('incomes'); }
};
