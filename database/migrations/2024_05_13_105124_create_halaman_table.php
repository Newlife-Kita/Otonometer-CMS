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
        Schema::create('halaman', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 36)->nullable();
            $table->enum('halaman_tipe', ['JELAJAH', 'UTAK-ATIK', 'BERKACA', 'LENSA']);
            $table->integer('view')->default(0);
            $table->integer('like')->default(0);
            $table->integer('simpan')->default(0);
            $table->year('tahun')->nullable()->default('0000');
            $table->integer('id_wilayah_1')->index('fk_halaman_master_wilayah');
            $table->integer('id_wilayah_2')->nullable();
            $table->string('dataset_1')->comment('data json state pilihan bidang halaman');
            $table->string('dataset_2')->nullable();
            $table->integer('parent_1')->nullable();
            $table->integer('parent_2')->nullable();
            $table->enum('ranking', ['PROVINSI', 'NASIONAL'])->nullable();
            $table->string('satuan', 150)->nullable();

            $table->index(['kode', 'tahun', 'id_wilayah_1', 'dataset_1'], 'halaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halaman');
    }
};
