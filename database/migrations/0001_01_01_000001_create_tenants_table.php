<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['active', 'suspended', 'expired'])->default('active');
            $table->date('subscription_ends_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tenants'); }
};
