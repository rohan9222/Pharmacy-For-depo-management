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
        Schema::create('sales_manager_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_team_id')->index()->nullOnDelete()->cascadeOnUpdate()->comment('user id who has manager role');
            $table->foreignId('user_id')->index()->nullOnDelete()->cascadeOnUpdate()->comment('user id who has field officer role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_manager_teams');
    }
};
