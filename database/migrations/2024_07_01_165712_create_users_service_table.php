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
        Schema::create('users_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('venues_id')->nullable();
            $table->unsignedBigInteger('promoters_id')->nullable();
            $table->unsignedBigInteger('other_service_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('venues_id')->references('id')->on('venues');
            $table->foreign('promoters_id')->references('id')->on('promoters');
            $table->foreign('other_service_id')->references('id')->on('other_services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_service');
    }
};