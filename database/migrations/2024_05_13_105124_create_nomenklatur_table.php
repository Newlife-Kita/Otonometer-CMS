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
        Schema::create('nomenklatur', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('kode', 30)->index('kode');
            $table->string('tahun_nomenklatur', 30)->nullable()->index('nomenklatur_tahun_nomenklatur_idx');
            $table->text('nama');
            $table->text('description');
            $table->integer('id_bidang')->nullable()->index('id_bidang');
            $table->enum('type', ['tahun', 'bidang', 'sektor'])->default('sektor');
            $table->integer('id_parent')->nullable()->index('nomenklatur_id_parent_idx');
            $table->integer('level')->nullable();
            $table->char('id_increament', 5)->nullable();
            $table->integer('id_satuan')->nullable()->index('id_satuan');
            $table->text('id_notes')->nullable();
            $table->text('id_sumber')->nullable();
            $table->enum('multi_select', ['y', 'n'])->nullable()->default('n');
            $table->enum('status', ['tampil', 'sembunyikan']);
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
        Schema::dropIfExists('nomenklatur');
    }
};
