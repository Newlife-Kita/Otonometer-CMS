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
        Schema::create('app_version', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('version_number', 50);
            $table->tinyInteger('current_active')->default(0);
            $table->tinyInteger('force_update')->default(1);
            $table->enum('platform', ['android', 'apple'])->default('android');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_version');
    }
};
