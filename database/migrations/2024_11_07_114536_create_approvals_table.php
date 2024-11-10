<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('approval.tables.name'), function (Blueprint $table) {
            $table->id();

            $table->string('hashslug')->unique();

            $table->bigInteger('approvable_id')->snullable()->index();
            $table->string('approvable_type')->nullable()->index();

            $table->integer('status')->default(1);

            $table->string('mark')->nullable();

            $table->boolean('approved')->default(false)->index();

            $table->text('remarks')->nullable();

            $table->json('modification')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable()->index();
            $table->unsignedBigInteger('rejected_by')->nullable()->index();

            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->timestamps();

            $table->index(['approvable_id', 'approvable_type']);

            $table->foreign('approved_by')
                ->references('id')
                ->on(config('approval.tables.approve_by'))
                ->nullOnDelete();

            $table->foreign('rejected_by')
                ->references('id')
                ->on(config('approval.tables.reject_by'))
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('approvals.table.name'));
    }
};
