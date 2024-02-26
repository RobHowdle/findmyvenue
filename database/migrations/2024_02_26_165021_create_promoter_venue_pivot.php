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
        Schema::create('promoter_venue_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promoters_id');
            $table->unsignedBigInteger('venues_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('promoters_id')->references('id')->on('promoters');
            $table->foreign('venues_id')->references('id')->on('venues');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoter_venue_pivot');
    }
};