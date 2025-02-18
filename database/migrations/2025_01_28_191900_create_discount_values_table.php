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
        Schema::create('discount_values', function (Blueprint $table) {
            $table->id();
            $table->decimal('start_amount', 11, 2);
            $table->decimal('end_amount', 11, 2); // Allows NULL for open-ended discounts
            $table->unsignedInteger('discount'); // Ensures no negative values
            $table->string('discount_type',50);
            $table->timestamps();

            // Ensure unique discount ranges to prevent overlap
            // $table->unique(['start_amount', 'end_amount']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_values');
    }
};
