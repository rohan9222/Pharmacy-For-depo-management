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
        Schema::create('manager_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->nullOnDelete()->cascadeOnUpdate()->index()->comment('user id who has manager role');
            $table->foreignId('user_id')->index()->nullOnDelete()->cascadeOnUpdate()->comment('user id who has Sales manager role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_teams');
    }
};
