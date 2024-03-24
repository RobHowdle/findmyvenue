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
        Schema::create('venue_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id');
            $table->string('communication_rating');
            $table->string('rop_rating');
            $table->string('promotion_rating');
            $table->string('quality_rating');
            $table->string('review_approved')->default(0);
            $table->longText('review');
            $table->string('author');
            $table->boolean('display')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('venue_id')->references('id')->on('venues');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_reviews');
    }
};