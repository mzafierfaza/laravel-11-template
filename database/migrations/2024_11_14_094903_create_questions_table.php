<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question_text')->nullable();
            $table->bool('is_essay')->nullable()->default(false);
            $table->integer('order')->default(0);
            $table->integer('points')->default(0);
            $table->integer('paket')->default(0);
            $table->text('correct_answer')->nullable();
            $table->text('choice_a')->nullable();
            $table->text('choice_b')->nullable();
            $table->text('choice_c')->nullable();
            $table->text('choice_d')->nullable();
            $table->text('choice_e')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
