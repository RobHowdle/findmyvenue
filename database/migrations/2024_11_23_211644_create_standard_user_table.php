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
        Schema::create('standard_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('postal_town');
            $table->string('longitude');
            $table->string('latitude');
            $table->json('band_type');
            $table->json('genre');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_user');
    }
};
