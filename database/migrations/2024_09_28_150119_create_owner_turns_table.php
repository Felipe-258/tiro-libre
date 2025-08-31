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
        Schema::create('owner_turns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_owner');
            $table->foreign('id_owner')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('id_field');
            $table->foreign('id_field')
                ->references('id')
                ->on('fields')
                ->onDelete('cascade');
            $table->string('player');
            $table->timestamp('day');
            $table->boolean('deny')->default(0);
            $table->tinyInteger('state')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_turns');
    }
};
