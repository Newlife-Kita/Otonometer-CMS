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
        Schema::create('member_aktifitas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('id_member')->index('id_member');
            $table->integer('id_kategori')->nullable()->index('id_kategori');
            $table->integer('id_wilayah')->nullable()->index('id_wilayah');
            $table->unsignedInteger('id_halaman')->index('halaman');
            $table->year('tahun')->nullable();
            $table->enum('activity', ['like', 'view', 'share', 'download', 'save']);
            $table->text('id_koleksi')->nullable()->comment('nama koleksi untuk menyimpan halaman');
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
        Schema::dropIfExists('member_aktifitas');
    }
};
