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
        Schema::create('master_bidang', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('kode', 30)->index('kode');
            $table->text('nama');
            $table->text('description');
            $table->text('tahun_nomenklatur')->nullable()->comment('tahun_nomenklatur digunakan untuk keuangan dan ekonomi, sedang untuk statistik kita gunakan grouping (0,1,2,3, dst) ');
            $table->integer('id_parent')->nullable()->index('id_parent_index');
            $table->integer('level')->nullable();
            $table->integer('id_increament')->nullable();
            $table->integer('id_satuan')->nullable();
            $table->text('id_notes')->nullable();
            $table->text('id_sumber')->nullable();
            $table->enum('multi_select', ['y', 'n'])->nullable()->default('n');
            $table->enum('flagging', ['all', 'province', 'city'])->default('all');
            $table->enum('contain_data', ['y', 'n'])->default('y');
            $table->enum('summable', ['y', 'n'])->default('y');
            $table->enum('root_selection', ['y', 'n'])->default('n');
            $table->enum('contain_alert', ['y', 'n'])->default('n');
            $table->text('content_alert')->nullable();
            $table->enum('status', ['tampil', 'sembunyikan']);
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
        Schema::dropIfExists('master_bidang');
    }
};
