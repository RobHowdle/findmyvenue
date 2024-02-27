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
        Schema::create('promoter_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promoter_id');
            $table->longText('review');
            $table->string('author');
            $table->boolean('display')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('promoter_id')->references('id')->on('promoters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoter_reviews');
    }
};