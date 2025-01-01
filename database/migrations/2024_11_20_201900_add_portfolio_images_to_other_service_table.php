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
        Schema::table('other_services', function (Blueprint $table) {
            $table->json('portfolio_images')->nullable()->after('portfolio_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_services', function (Blueprint $table) {
            $table->dropColumn('portfolio_images');
        });
    }
};
