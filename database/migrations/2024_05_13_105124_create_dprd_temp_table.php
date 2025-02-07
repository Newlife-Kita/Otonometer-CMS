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
        Schema::create('dprd_temp', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_wilayah')->nullable();
            $table->integer('id_komisi')->nullable();
            $table->integer('id_jabatan')->nullable();
            $table->integer('id_partai')->nullable();
            $table->string('nama_lengkap', 150);
            $table->text('foto')->nullable();
            $table->year('tahun')->nullable();
            $table->year('tahun_lantik')->nullable();
            $table->year('tahun_akhir')->nullable();
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
        Schema::dropIfExists('dprd_temp');
    }
};
