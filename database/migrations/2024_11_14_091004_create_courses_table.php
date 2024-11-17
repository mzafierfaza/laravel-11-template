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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('procedurs')->nullable();
            $table->text('topic')->nullable();
            $table->enum('format', ['online', 'offline', 'webinar'])->default('online');
            $table->boolean(('is_random_material'))->default(false);
            $table->boolean(('is_premium'))->default(false);
            $table->float('price')->default(0);
            $table->foreignId('created_by')->constrained('dashin_users');
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_repeat_enrollment')->default(false);
            $table->integer('max_repeat_enrollment')->default(0);
            $table->integer('max_enrollment')->default(0);
            $table->boolean(('is_class_test'))->default(false);
            $table->boolean(('is_class_finish'))->default(false);
            $table->boolean('status')->default(true);
            $table->integer(('approved_status'))->default(0);
            $table->timestamp('approved_at')->nullable();
            // $table->foreign('user_id')->references('id')->on('dashin_users')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('approved_by')->references(columns: 'id')->on('dashin_users')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('teacher_id')->references(columns: 'id')->on('dashin_users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('approved_by')->constrained('dashin_users');
            $table->foreignId('teacher_id')->constrained('dashin_users');
            $table->text('teacher_about')->nullable();
            $table->text('image')->nullable();
            $table->text('certificate')->nullable();
            $table->boolean('certificate_can_download')->default(true);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
