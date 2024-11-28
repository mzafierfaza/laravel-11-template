<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('core_users');
            $table->foreignId('competence_id')->constrained('competences');
            $table->timestamp('enrolled_date')->nullable();
            $table->timestamp('graduated_date')->nullable();
            $table->string('enrollment_status')->nullable();
            $table->string('certificate')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
