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
        Schema::create('suku_dinas_pejabat_temp', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_suku_dinas');
            $table->string('nama_lengkap', 150);
            $table->integer('id_jabatan')->nullable();
            $table->year('tahun_lantik');
            $table->year('tahun_akhir');
            $table->year('tahun')->nullable();
            $table->text('foto')->nullable();
            $table->string('nip', 50)->nullable();
            $table->string('contact', 100)->nullable();
            $table->string('email', 150)->nullable();
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
        Schema::dropIfExists('suku_dinas_pejabat_temp');
    }
};
