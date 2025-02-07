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
        Schema::table('dprd', function (Blueprint $table) {
            $table->foreign(['id_komisi'], 'dprd_ibfk_1')->references(['id'])->on('master_komisi')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_jabatan'], 'dprd_ibfk_2')->references(['id'])->on('master_jabatan')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_partai'], 'dprd_ibfk_3')->references(['id'])->on('master_partai')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dprd', function (Blueprint $table) {
            $table->dropForeign('dprd_ibfk_1');
            $table->dropForeign('dprd_ibfk_2');
            $table->dropForeign('dprd_ibfk_3');
        });
    }
};
