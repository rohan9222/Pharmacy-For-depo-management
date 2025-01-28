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
        Schema::create('sales_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('medicine_id')->nullable()->constrained()->restrictOnDelete()->cascadeOnUpdate();
            // $table->string('medicine_name');
            $table->string('batch_number', 50)->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->integer('initial_quantity')->default(1)->comment('sales medicine initial quantity which is on sales invoice');
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
        Schema::dropIfExists('sales_medicines');
    }
};
