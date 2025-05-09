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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->decimal('balance', 11, 2)->default('0.00')->nullable();
            $table->string('role')->default('user');
            $table->string('route')->nullable();
            $table->string('category')->nullable();

            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has manager role');
            $table->foreignId('zse_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has Zonal Sales Executive role');
            $table->foreignId('tse_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate()->comment('user id who has Territory Sales Executive role');
            
            $table->longtext('product_target_data')->nullable();
            $table->decimal('product_target', 11, 2)->nullable()->default(0.00);
            $table->decimal('sales_target', 11, 2)->nullable()->default(0.00);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
