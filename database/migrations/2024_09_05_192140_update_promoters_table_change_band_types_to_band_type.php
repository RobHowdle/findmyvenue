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
            $table->renameColumn('band_types', 'band_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('band_type', function (Blueprint $table) {
            $table->renameColumn('band_type', 'band_types');
        });
    }
};
