<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->string('module_id', 191);
            $table->foreign('module_id')->on('modules')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title', 191)->nullable();
            $table->text('description')->nullable();
            $table->integer('order', 191)->nullable();
            $table->integer('duration_minutes', 191)->nullable();
            $table->integer('passing_score', 191)->nullable();
            $table->timestamp('start_time', 191)->nullable();
            $table->timestamp('end_time', 191)->nullable();
            $table->bool('is_randomize')->nullable();
            $table->bool('is_essay')->nullable();
            $table->string('type', 191)->nullable();
            $table->string('file_path', 191)->nullable();
            $table->timestamps();
            $table->string('deleted_at', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}
