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
        Schema::create('master_ekonomi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('kode', 20)->nullable()->index('kode')->comment('Kode otomatis dibuat oleh sistem');
            $table->string('nama', 150)->nullable()->index('nama');
            $table->text('icon_light_mode')->nullable();
            $table->text('icon_dark_mode')->nullable();
            $table->enum('status', ['tampil', 'sembunyikan'])->nullable()->default('sembunyikan');
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
        Schema::dropIfExists('master_ekonomi');
    }
};
