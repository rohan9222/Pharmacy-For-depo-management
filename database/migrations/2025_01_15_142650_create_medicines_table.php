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
            $table->string('barcode', 50)->unique();
            // $table->string('product_id')->unique();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('description')->nullable();
            $table->string('shelf')->nullable();
            $table->string('category_name')->nullable()
                    ->comment('user id who has manager role')
                    ->foreign('category_name')
                    ->references('name')->on('categories')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            $table->string('image_url')->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('supplier_price', 11, 3)->nullable()->default(0.00);
            $table->decimal('price', 11, 3)->nullable()->default(0.00);
            $table->decimal('discount', 11, 3)->nullable()->default(0.00);
            $table->enum('dis_type', ['percentage', 'fixed'])->default('percentage')->nullable();
            $table->decimal('dis_amount', 11, 3)->default(0.00)->nullable();
            $table->decimal('vat', 11, 3)->nullable()->default(0.00);
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
