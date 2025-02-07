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
        Schema::create('member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_sso')->nullable();
            $table->string('name');
            $table->string('title', 50)->nullable();
            $table->string('email')->unique('users_email_unique');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile', 50)->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('image')->nullable();
            $table->integer('id_wilayah')->nullable();
            $table->string('password')->nullable();
            $table->enum('manual_login', ['y', 'n'])->default('n');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->string('postal_code', 100)->nullable();
            $table->text('address')->nullable();
            $table->text('website')->nullable();
            $table->text('sso_access_token')->nullable();
            $table->integer('job_id')->nullable();
            $table->integer('package_id')->nullable();
            $table->integer('reference_id')->nullable();
            $table->integer('provider_id')->nullable();
            $table->string('provider_name', 100)->nullable();
            $table->dateTime('update_profile_daily')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member');
    }
};
