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
        Schema::create('snake_ladders', function (Blueprint $table) {
            $table->id();
            $table->integer('start');
            $table->integer('end');
            $table->enum('type', ['snake', 'ladder']); // ular atau tangga
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snake_ladders');
    }
};
