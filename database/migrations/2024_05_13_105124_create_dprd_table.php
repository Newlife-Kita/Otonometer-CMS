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
        Schema::create('dprd', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_wilayah')->index('id_wilayah');
            $table->integer('id_komisi')->nullable()->index('id_komisi');
            $table->integer('id_jabatan')->nullable()->index('id_jabatan');
            $table->integer('id_partai')->index('id_partai');
            $table->string('nama_lengkap', 150)->index('nama_lengkap');
            $table->text('foto')->nullable();
            $table->year('tahun');
            $table->year('tahun_lantik')->index('tahun');
            $table->year('tahun_akhir');
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
        Schema::dropIfExists('dprd');
    }
};
