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
        Schema::create('master_jabatan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_increament')->nullable();
            $table->string('kode', 20)->nullable()->index('kode')->comment('Unik dikelompokan berdasarkan tipe');
            $table->string('nama', 150)->nullable()->index('nama');
            $table->enum('tipe', ['pemda', 'dprd', 'dinas'])->nullable()->index('tipe');
            $table->enum('tipe_wilayah', ['propinsi', 'kabupaten', 'kota'])->nullable();
            $table->integer('urutan')->nullable()->comment('Urut Berdasarka Tipe');
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
        Schema::dropIfExists('master_jabatan');
    }
};
