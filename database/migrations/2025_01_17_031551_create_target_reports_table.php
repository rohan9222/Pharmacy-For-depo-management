<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('target_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('role')->nullable();
            $table->foreignId('manager')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has manager role');
            $table->foreignId('sales_manager')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has sales manager role');
            $table->foreignId('field_officer')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has field officer role');
            $table->foreignId('customer')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has customer role');
            $table->string('product_target')->nullable();
            $table->boolean('product_target_achieve')->nullable();
            $table->string('sales_target')->nullable();
            $table->boolean('sales_target_achieve')->nullable();
            $table->string('target_month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_reports');
    }
};
