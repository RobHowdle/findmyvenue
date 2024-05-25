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
        Schema::table('promoters', function (Blueprint $table) {
            $table->string('postal_town')->after('location');
            $table->string('latitude')->after('postal_town');
            $table->string('longitude')->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promoters', function (Blueprint $table) {
            $table->dropColumn('postal_town');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
};
