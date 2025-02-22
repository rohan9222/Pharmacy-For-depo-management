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
        Schema::create('stock_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_invoice_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('payment_methods_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 11, 2)->default(0.00);
            $table->string('date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_payment_histories');
    }
};
