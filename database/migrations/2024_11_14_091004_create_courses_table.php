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
            $table->text('format')->nullable()->default('online');
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
            $table->boolean('is_repeat_enrollment')->nullable()->default(false);
            $table->integer('max_repeat_enrollment')->nullable()->default(0);
            $table->integer('max_enrollment')->nullable()->default(0);
            $table->boolean(('is_class_test'))->nullable()->default(false);
            $table->boolean(('is_class_finish'))->nullable()->default(false);
            $table->boolean('status')->nullable()->default(true);
            $table->integer(('approved_status'))->nullable()->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('dashin_users');
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