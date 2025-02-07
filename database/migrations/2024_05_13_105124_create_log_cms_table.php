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
        Schema::create('log_cms', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('users_id');
            $table->integer('row_uploaded')->default(0);
            $table->integer('row_submitted')->default(0);
            $table->dateTime('upload_date')->nullable();
            $table->dateTime('submit_date')->nullable();
            $table->enum('status', ['submitted', 'deleted', 'pending'])->default('pending');
            $table->string('upload_type', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_cms');
    }
};
