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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 50)->unique()->comment('invoice must be unique');
            // $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->comment('customer list id');
            $table->timestamp('invoice_date')->default(now());
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has manager role');
            $table->foreignId('sales_manager_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has sales manager role');
            $table->foreignId('field_officer_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has field officer role');
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has customer role');
            $table->decimal('sub_total', 11, 2)->default(0.00);
            $table->decimal('discount', 11, 2)->default(0.00)->nullable();
            $table->enum('dis_type', ['percentage', 'fixed'])->default('percentage')->nullable();
            $table->decimal('dis_amount', 11, 2)->default(0.00)->nullable();
            $table->decimal('spl_discount', 11, 2)->default(0.00)->nullable();
            $table->enum('spl_dis_type', ['percentage', 'fixed'])->default('percentage')->nullable();
            $table->decimal('spl_dis_amount', 11, 2)->default(0.00)->nullable();
            $table->decimal('vat', 11, 2)->default(0.00)->nullable();
            $table->decimal('tax', 11, 2)->default(0.00)->nullable();
            $table->decimal('grand_total', 11, 2)->default(0.00);
            $table->decimal('paid', 11, 2)->default(0.00)->nullable();
            $table->decimal('due', 11, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
