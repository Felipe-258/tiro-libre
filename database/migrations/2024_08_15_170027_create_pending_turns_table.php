<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_turns', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_player');
            $table->foreign('id_player')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('id_field');
            $table->foreign('id_field')
                ->references('id')
                ->on('fields')
                ->onDelete('cascade');
            $table->timestamp('day');
            $table->boolean('deny')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_turns');
    }
};
