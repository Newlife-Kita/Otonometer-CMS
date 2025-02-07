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
        Schema::create('master_paket', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('nama_paket')->nullable();
            $table->text('icon')->nullable();
            $table->decimal('biaya', 10, 0)->nullable();
            $table->integer('periode')->nullable();
            $table->enum('periode_label', ['day', 'week', 'month', 'year'])->nullable()->default('day');
            $table->enum('status', ['tampil', 'sembunyikan'])->nullable();
            $table->integer('total_download')->nullable();
            $table->integer('total_save')->nullable();
            $table->integer('total_collection')->default(0);
            $table->integer('total_save_per_collection')->default(0);
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
        Schema::dropIfExists('master_paket');
    }
};
