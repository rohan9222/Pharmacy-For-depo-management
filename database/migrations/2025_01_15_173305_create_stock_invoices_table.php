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
        Schema::create('stock_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 50)->unique();
            $table->timestamp('invoice_date')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate()->comment('supplier list id');
            $table->decimal('sub_total', 11, 2)->default(0.00)->nullable();
            $table->decimal('discount', 11, 2)->default(0.00)->nullable();
            $table->enum('dis_type', ['percentage', 'fixed'])->default('percentage')->nullable();
            $table->decimal('dis_amount', 11, 2)->default(0.00)->nullable();
            $table->decimal('vat', 11, 2)->default(0.00)->nullable();
            $table->decimal('total', 11, 2)->default(0.00)->nullable();
            $table->decimal('paid', 11, 2)->default(0.00)->nullable();
            $table->decimal('due', 11, 2)->default(0.00)->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     **/
    public function down(): void
    {
        Schema::dropIfExists('stock_invoices');
    }
};
