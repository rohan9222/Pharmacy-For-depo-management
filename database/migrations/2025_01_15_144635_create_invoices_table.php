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
            $table->timestamp('invoice_date')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has manager role');
            $table->foreignId('zse_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has Zonal Sales Executive role');
            $table->foreignId('tse_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has Territory Sales Executive role');
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has customer role');
            $table->decimal('sub_total', 11, 2)->default(0.00);
            $table->decimal('discount', 11, 2)->default(0.00)->nullable();
            $table->string('invoice_data')->nullable();
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
            $table->enum('delivery_status',['pending','cancel','delivered','return','shipped'])->default('pending')->nullable();
            $table->foreignId('delivery_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has delivery role');
            $table->timestamp('delivery_date')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->integer('summary_id')->nullable();
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
