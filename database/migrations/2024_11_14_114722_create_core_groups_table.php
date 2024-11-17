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
        Schema::create('core_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('jenis_badan_usaha', ['perorangan', 'perusahaan'])->default('perorangan');
            $table->string('bidang_usaha')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_ktp')->nullable();
            $table->string('owner_npwp')->nullable();
            $table->string('address')->nullable();
            $table->string('pic_name')->nullable();
            $table->string('pic_phone')->nullable();
            $table->string('pic_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_groups');
    }
};
