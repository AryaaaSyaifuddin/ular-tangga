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
            // Changed from foreignId with constraint to json to support multiple materis
            $table->json('materi_id')->comment('Array of selected materi IDs');
            $table->integer('jumlah_pemain');
            $table->enum('status', ['waiting', 'playing', 'finished'])->default('waiting');
            $table->unsignedBigInteger('current_question_id')->nullable();
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
