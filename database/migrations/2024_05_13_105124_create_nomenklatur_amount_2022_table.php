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
        Schema::create('nomenklatur_amount_2022', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_bidang')->nullable()->index('id_bidang');
            $table->integer('id_nomenklatur')->nullable();
            $table->integer('id_wilayah')->nullable()->index('id_wilayah');
            $table->decimal('nilai', 16, 3)->nullable();
            $table->year('tahun')->nullable()->index('tahun');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable()->index('deleted_at');
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
        Schema::dropIfExists('nomenklatur_amount_2022');
    }
};
