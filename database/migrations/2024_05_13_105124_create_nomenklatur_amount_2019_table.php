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
        Schema::create('nomenklatur_amount_2019', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_bidang')->nullable()->index();
            $table->integer('id_nomenklatur')->nullable();
            $table->integer('id_wilayah')->nullable()->index();
            $table->year('tahun')->default('2019')->index();
            $table->decimal('nilai', 16, 3)->nullable()->index();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->longText('history_updated')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id_bidang', 'id_wilayah']);
            $table->index(['id_bidang', 'id_wilayah', 'nilai']);
            $table->index(['id_bidang', 'id_wilayah', 'tahun', 'nilai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomenklatur_amount_2019');
    }
};
