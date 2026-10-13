<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('company_name')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_number')->nullable();
            $table->json('theme')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('company_settings'); }
};
