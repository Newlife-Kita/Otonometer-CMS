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
        Schema::create('master_kodepos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_wilayah')->nullable()->index('id_wilayah');
            $table->integer('id_parent')->nullable();
            $table->integer('id_increament')->nullable();
            $table->enum('type', ['kecamatan', 'kelurahan'])->nullable()->default('kelurahan');
            $table->string('kode', 30)->nullable();
            $table->string('kodepos', 6)->nullable()->index('kodepos');
            $table->string('nama', 100)->nullable()->index('nama');
            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();
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
        Schema::dropIfExists('master_kodepos');
    }
};
