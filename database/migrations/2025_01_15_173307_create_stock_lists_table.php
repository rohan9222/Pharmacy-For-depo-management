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
        Schema::create('stock_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('stock_invoice_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('batch_number', 50)->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 11, 3)->default(0.00);
            $table->decimal('buy_price', 11, 3)->default(0.00);
            $table->decimal('vat', 11, 3)->default(0.00);
            $table->decimal('total', 11, 3)->default(0.00);
            $table->decimal('discount', 11, 3)->default(0.00);
            $table->enum('dis_type', ['percentage', 'fixed'])->default('percentage')->nullable();
            $table->decimal('dis_amount', 11, 3)->default(0.00)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_lists');
    }
};
