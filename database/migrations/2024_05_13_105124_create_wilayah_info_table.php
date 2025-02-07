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
        Schema::create('wilayah_info', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_wilayah')->nullable()->index('id_wilayah');
            $table->year('tahun')->nullable();
            $table->string('id_sektor', 20)->nullable()->default('0')->index('id_pdrb');
            $table->string('nilai_sektor', 100)->nullable()->index('nilai_pdrb');
            $table->string('ketinggian', 100)->nullable();
            $table->string('luas_wilayah', 100)->nullable();
            $table->string('jumlah_penduduk', 100)->nullable();
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
        Schema::dropIfExists('wilayah_info');
    }
};
