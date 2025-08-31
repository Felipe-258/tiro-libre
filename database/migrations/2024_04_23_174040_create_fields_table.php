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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_owner');
            $table->foreign('id_owner')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->text('coordinates');
            $table->string('name');
            $table->integer('capacity');
            $table->string('type');
            $table->integer('price');
            $table->text('description')->nullable();
            $table->time('start');
            $table->time('end');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
