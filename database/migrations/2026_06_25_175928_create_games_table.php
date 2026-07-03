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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            
            // Relational Database Requirement: Links games to TWO different teams
            $table->foreignId('team1_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('team2_id')->constrained('teams')->onDelete('cascade');
            
            $table->dateTime('match_time');
            $table->string('status')->default('Upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
