<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['draft', 'sent', 'received', 'cancelled'])->default('draft');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->date('order_date');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_orders'); }
};
