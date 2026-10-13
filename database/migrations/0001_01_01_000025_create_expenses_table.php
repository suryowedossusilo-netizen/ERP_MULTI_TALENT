<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('expense_number')->unique();
            $table->string('category')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('expenses'); }
};
