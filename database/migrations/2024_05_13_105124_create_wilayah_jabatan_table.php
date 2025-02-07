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
        Schema::create('wilayah_jabatan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_wilayah')->index('id_wilayah');
            $table->string('nama_lengkap', 150)->nullable()->index('nama_lengkap');
            $table->integer('id_jabatan')->index('id_jabatan');
            $table->year('tahun')->nullable();
            $table->year('tahun_lantik')->index('tahun');
            $table->year('tahun_akhir')->nullable();
            $table->text('foto')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->longText('history_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_jabatan');
    }
};
