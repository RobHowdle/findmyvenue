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
        Schema::create('venues_extra_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venues_id');
            $table->longText('text');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('venues_id')->references('id')->on('venues');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues_extra_info');
    }
};