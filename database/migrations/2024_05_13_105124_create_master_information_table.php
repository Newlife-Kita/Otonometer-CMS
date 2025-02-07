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
        Schema::create('master_information', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('file')->nullable();
            $table->string('nama_file', 150)->nullable();
            $table->text('text_format')->nullable();
            $table->enum('bidang', ['Ekonomi', 'Keuangan', 'Statistik'])->nullable()->index('bidang_index');
            $table->enum('status', ['tampil', 'sembunyikan'])->index('status_index');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('master_information');
    }
};
