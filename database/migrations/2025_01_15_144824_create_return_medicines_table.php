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
        Schema::create('return_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('medicine_id')->nullable()->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('sales_medicine_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('return_date')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            // $table->string('medicine_name');
            $table->string('batch_number', 50)->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 11, 2)->default(0.00)->nullable();
            $table->decimal('discount', 11, 2)->default(0.00)->nullable();
            $table->enum('dis_type', ['percentage', 'fixed'])->default('percentage')->nullable();
            $table->decimal('dis_amount', 11, 2)->default(0.00)->nullable();
            $table->decimal('vat', 11, 2)->default(0.00);
            $table->decimal('total', 11, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_medicines');
    }
};
