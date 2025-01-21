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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id()->startingValue(10000);;
            $table->string('barcode')->unique();
            // $table->string('product_id')->unique();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('description')->nullable();
            $table->string('shelf')->nullable();
            $table->string('category_name')->nullable();
            $table->string('image_url')->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('supplier_price', 11, 2)->nullable()->default(0.00);
            $table->decimal('price', 11, 2)->nullable()->default(0.00);
            $table->decimal('discount', 11, 2)->nullable()->default(0.00);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage')->nullable();
            $table->decimal('dis_amount', 11, 2)->default(0.00)->nullable();
            $table->decimal('vat', 11, 2)->nullable()->default(0.00);
            $table->integer('quantity')->nullable()->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
