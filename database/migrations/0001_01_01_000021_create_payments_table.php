<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('payment_number')->unique();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['in', 'out']);
            $table->decimal('amount', 15, 2);
            $table->string('method')->default('cash');
            $table->date('payment_date');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
