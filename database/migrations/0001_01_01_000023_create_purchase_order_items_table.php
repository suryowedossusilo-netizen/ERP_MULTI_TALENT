<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity', 15, 3);
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('received_quantity', 15, 3)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_order_items'); }
};
