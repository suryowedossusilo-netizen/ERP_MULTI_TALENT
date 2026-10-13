<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('numbering_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('module'); // order, invoice, po, payment, income, expense
            $table->string('prefix')->default('');
            $table->unsignedInteger('next_number')->default(1);
            $table->unsignedInteger('padding')->default(5);
            $table->timestamps();
            $table->unique(['tenant_id', 'module']);
        });
    }
    public function down(): void { Schema::dropIfExists('numbering_settings'); }
};
