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
        Schema::create('photography_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('other_services_id');
            $table->unsignedBigInteger('other_services_list_id');
            $table->string('communication_rating');
            $table->string('flexibility_rating');
            $table->string('professionalism_rating');
            $table->string('photo_quality_rating');
            $table->string('price_rating');
            $table->string('review_approved')->default(0);
            $table->longText('review');
            $table->string('author');
            $table->boolean('display')->default(0);
            $table->string('reviewer_ip');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('other_services_id')->references('id')->on('other_services');
            $table->foreign('other_services_list_id')->references('id')->on('other_services_list');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photography_reviews');
    }
};
