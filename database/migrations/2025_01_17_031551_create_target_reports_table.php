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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            // $table->string('role')->nullable();
            $table->foreignId('manager')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has manager role');
            $table->foreignId('zse')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has Zonal Sales Executive role');
            $table->foreignId('tse')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has Territory Sales Executive role');
            // $table->foreignId('customer')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has customer role');
            $table->longtext('product_target_data')->nullable();
            $table->decimal('product_target', 11, 2)->nullable()->default(0.00);
            $table->decimal('product_target_achieve', 11, 2)->nullable()->default(0.00);
            $table->decimal('sales_target', 11, 2)->nullable()->default(0.00);
            $table->decimal('sales_target_achieve', 11, 2)->nullable()->default(0.00);
            $table->string('target_month',50)->nullable();
            $table->smallInteger('target_year')->nullable();
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
