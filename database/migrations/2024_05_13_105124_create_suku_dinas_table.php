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
        Schema::create('suku_dinas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_wilayah')->index('id_wilayah');
            $table->string('nama_sudin', 150)->index('nama_sudin');
            $table->text('alamat')->nullable();
            $table->text('logo')->nullable();
            $table->string('nama_pic', 150)->nullable()->index('nama_pic');
            $table->string('telp_pic', 100)->nullable();
            $table->string('email_pic', 150)->nullable();
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
        Schema::dropIfExists('suku_dinas');
    }
};
