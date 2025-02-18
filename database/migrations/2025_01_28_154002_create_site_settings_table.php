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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('site_title')->nullable();
            $table->string('site_email')->nullable();
            $table->string('site_phone')->nullable();
            $table->string('site_address')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('site_description')->nullable();
            $table->string('site_keywords')->nullable();
            $table->string('site_author')->nullable();
            $table->string('site_status')->nullable();
            $table->string('site_maintenance')->nullable();
            $table->string('site_message')->nullable();
            $table->string('site_facebook')->nullable();
            $table->string('site_twitter')->nullable();
            $table->string('site_instagram')->nullable();
            $table->string('site_linkedin')->nullable();
            $table->string('site_pinterest')->nullable();
            $table->string('site_youtube')->nullable();
            $table->string('site_whatsapp')->nullable();
            $table->string('site_map')->nullable();
            $table->string('site_currency')->nullable();
            $table->string('site_invoice_prefix')->nullable();
            $table->string('site_invoice_logo')->nullable();
            $table->string('site_invoice_color')->nullable();
            $table->string('site_invoice_footer')->nullable();
            $table->string('site_invoice_notes')->nullable();
            $table->string('site_invoice_terms')->nullable();
            $table->string('site_invoice_signature')->nullable();
            $table->string('medicine_expiry_alert')->nullable();
            $table->string('medicine_expiry_days')->nullable();
            $table->string('medicine_low_stock_alert')->nullable();
            $table->string('medicine_low_stock_quantity')->nullable();
            $table->string('site_secret_key')->nullable();
            $table->string('site_secret_value')->nullable();
            $table->string('site_secret_validity')->nullable();
            $table->string('site_secret_url')->nullable();
            $table->string('site_secret_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
