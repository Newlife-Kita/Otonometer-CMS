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
        Schema::create('wilayah_jabatan_temp', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_wilayah');
            $table->string('nama_lengkap', 150);
            $table->integer('id_jabatan')->nullable();
            $table->year('tahun');
            $table->year('tahun_lantik');
            $table->year('tahun_akhir')->nullable();
            $table->text('foto')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->longText('history')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_jabatan_temp');
    }
};
