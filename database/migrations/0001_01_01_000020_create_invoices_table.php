<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('paid', 15, 2)->default(0);
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])->default('unpaid');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('invoices'); }
};
