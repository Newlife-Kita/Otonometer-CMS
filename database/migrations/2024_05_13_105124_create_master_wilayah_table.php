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
        Schema::create('master_wilayah', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_increament')->nullable()->comment('Increament berdasarkan id_parent');
            $table->string('kode', 20)->nullable()->index('kode');
            $table->enum('tipe', ['propinsi', 'kabupaten', 'kota'])->default('propinsi')->index('tipe');
            $table->string('nama', 150)->nullable()->index('nama');
            $table->text('alamat_kantor_pemerintahan')->nullable()->comment('Alamat Kantor Pemerintahan');
            $table->text('alamat_kantor_dprd')->nullable()->comment('Alamat Kantor DPRD');
            $table->string('kodepos', 10)->nullable()->index('kodepos');
            $table->text('logo')->nullable();
            $table->text('peta_light_mode')->nullable();
            $table->text('peta_dark_mode')->nullable();
            $table->string('longitude', 150)->nullable()->index('koordinat');
            $table->string('latitude', 100)->nullable();
            $table->year('tahun_pendirian')->default('1945');
            $table->year('tahun_pembubaran')->nullable();
            $table->integer('id_parent')->nullable()->index('id_parent_index')->comment('Null jika tipe = propinsi, jika kabupaten/ kota maka id_parent  = id propinsi');
            $table->integer('id_dataran')->nullable()->index('id_dataran');
            $table->integer('id_ekonomi')->nullable()->index('id_ekonomi');
            $table->integer('has_data')->nullable()->default(0)->index('has_data_index');
            $table->enum('status', ['tampil', 'sembunyikan'])->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_wilayah');
    }
};
