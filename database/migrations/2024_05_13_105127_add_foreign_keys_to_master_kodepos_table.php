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
        Schema::table('master_kodepos', function (Blueprint $table) {
            $table->foreign(['id_wilayah'], 'master_kodepos_ibfk_1')->references(['id'])->on('master_wilayah')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_kodepos', function (Blueprint $table) {
            $table->dropForeign('master_kodepos_ibfk_1');
        });
    }
};
