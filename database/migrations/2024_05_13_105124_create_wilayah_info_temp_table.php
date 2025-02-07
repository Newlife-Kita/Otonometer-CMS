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
        Schema::create('wilayah_info_temp', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_wilayah');
            $table->year('tahun');
            $table->integer('id_sektor')->nullable();
            $table->string('nilai_sektor', 100)->nullable();
            $table->string('ketinggian', 100)->nullable();
            $table->string('luas_wilayah', 100)->nullable();
            $table->string('jumlah_penduduk', 100)->nullable();
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
        Schema::dropIfExists('wilayah_info_temp');
    }
};
