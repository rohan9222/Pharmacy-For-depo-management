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
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('role')->nullable();
            $table->timestamps();
            $table->foreignId('manager')->constrained('users')->restrictOnDelete()->cascadeOnUpdate()->comment('user id who has manager role');
            $table->foreignId('sales_manager')->constrained('users')->restrictOnDelete()->cascadeOnUpdate()->comment('user id who has sales manager role');
            $table->foreignId('field_officer')->constrained('users')->restrictOnDelete()->cascadeOnUpdate()->comment('user id who has field officer role');
            $table->unique(['team_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_invitations');
    }
};
