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
        Schema::create('player_rooms', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_room');
            $table->foreign('id_room')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');
            $table->unsignedBigInteger('id_player');
            $table->foreign('id_player')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_rooms');
    }
};
